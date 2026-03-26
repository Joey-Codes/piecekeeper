<?php

namespace App\Services;

use App\Models\Piece;
use App\Models\PracticeSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PracticeSessionService
{
    /**
     * Get or create today's practice session for the given user.
     */
    public function getOrCreateForToday(User $user): PracticeSession
    {
        $today = Carbon::today();

        $session = $user->sessions()
            ->whereDate('date', $today)
            ->with('pieces')
            ->first();

        if ($session) {
            return $session;
        }

        return $this->createSession($user, $today);
    }

    /**
     * Build a new session: pick pieces via rotation, persist, and advance the index.
     */
    protected function createSession(User $user, Carbon $date): PracticeSession
    {
        $activePieces = $user->pieces()
            ->whereNotNull('sort_order')
            ->orderBy('sort_order')
            ->get();

        $total = $activePieces->count();

        if ($total === 0) {
            return $this->createEmptySession($user, $date);
        }

        $perSession = min($user->pieces_per_session ?? $total, $total);
        $rotationIndex = $user->rotation_index ?? 0;

        // Pick pieces starting from rotation_index, wrapping around
        $assigned = collect();
        for ($i = 0; $i < $perSession; $i++) {
            $assigned->push($activePieces[($rotationIndex + $i) % $total]);
        }

        $newRotationIndex = ($rotationIndex + $perSession) % $total;

        return DB::transaction(function () use ($user, $date, $assigned, $perSession, $newRotationIndex) {
            $session = $user->sessions()->create([
                'date' => $date,
                'pieces_assigned' => $perSession,
                'pieces_completed' => 0,
                'duration_seconds' => 0,
            ]);

            foreach ($assigned as $position => $piece) {
                $session->pieces()->attach($piece->id, [
                    'position' => $position + 1,
                    'completed' => false,
                ]);
            }

            $user->update(['rotation_index' => $newRotationIndex]);

            return $session->load('pieces');
        });
    }

    protected function createEmptySession(User $user, Carbon $date): PracticeSession
    {
        return $user->sessions()->create([
            'date' => $date,
            'pieces_assigned' => 0,
            'pieces_completed' => 0,
            'duration_seconds' => 0,
        ]);
    }
}
