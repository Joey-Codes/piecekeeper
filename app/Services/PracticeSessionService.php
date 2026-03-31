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
        $today = Carbon::today($user->timezone ?? 'UTC');

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
     * Build a new session: pick pieces via rotation and persist.
     * The rotation index is NOT advanced here — it advances when the session is finished,
     * or when the next session is created (if the previous one was abandoned).
     */
    protected function createSession(User $user, Carbon $date): PracticeSession
    {
        if (! $user->rollover_skipped) {
            $this->advancePastAbandonedSession($user);
        }

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

        return DB::transaction(function () use ($user, $date, $assigned, $perSession) {
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

            return $session->load('pieces');
        });
    }

    /**
     * If the most recent session was never finished (duration_seconds = 0 with pieces assigned),
     * advance the rotation index past it so those pieces aren't reassigned.
     */
    protected function advancePastAbandonedSession(User $user): void
    {
        $lastSession = $user->sessions()
            ->where('pieces_assigned', '>', 0)
            ->latest('date')
            ->first();

        if (!$lastSession || $lastSession->duration_seconds > 0) {
            return;
        }

        $total = $user->pieces()
            ->whereNotNull('sort_order')
            ->count();

        if ($total === 0) {
            return;
        }

        $rotationIndex = $user->rotation_index ?? 0;
        $newIndex = ($rotationIndex + $lastSession->pieces_assigned) % $total;
        $user->update(['rotation_index' => $newIndex]);
    }

    /**
     * Advance the rotation index after a session is completed.
     * If rollover_skipped is enabled, reorder so skipped pieces appear right at the new index.
     */
    public function advanceRotationIndex(User $user, PracticeSession $session): void
    {
        $activePieces = $user->pieces()
            ->whereNotNull('sort_order')
            ->orderBy('sort_order')
            ->get();

        $total = $activePieces->count();

        if ($total === 0) {
            return;
        }

        $rotationIndex = $user->rotation_index ?? 0;
        $newIndex = ($rotationIndex + $session->pieces_assigned) % $total;

        if ($user->rollover_skipped) {
            $skippedIds = $session->pieces()
                ->wherePivot('completed', false)
                ->pluck('pieces.id')
                ->all();

            if (!empty($skippedIds)) {
                $this->rolloverSkippedPieces($user, $activePieces, $newIndex, $skippedIds);
                return;
            }
        }

        $user->update(['rotation_index' => $newIndex]);
    }

    /**
     * Reorder so that the list becomes: [completed pieces] [skipped pieces] [rest],
     * with the rotation index pointing right after the completed pieces (at the skipped ones).
     * This preserves the "you were here" context in the Up Next view.
     *
     * Example: order A B C D E, assigned C D E, completed D E, skipped C
     *   → new order: D E C A B, index at 2 (pointing at C)
     */
    protected function rolloverSkippedPieces(User $user, $activePieces, int $newIndex, array $skippedIds): void
    {
        $total = $activePieces->count();
        $rotationIndex = $user->rotation_index ?? 0;
        $skippedIdSet = array_flip($skippedIds);

        // Get the assigned piece IDs (from rotationIndex to newIndex)
        $assignedCount = ($newIndex - $rotationIndex + $total) % $total ?: $total;
        $assignedIds = [];
        for ($i = 0; $i < $assignedCount; $i++) {
            $assignedIds[] = $activePieces[($rotationIndex + $i) % $total]->id;
        }
        $assignedIdSet = array_flip($assignedIds);

        // Split assigned pieces into completed and skipped (preserving session order)
        $completed = collect();
        $skipped = collect();
        foreach ($assignedIds as $id) {
            $piece = $activePieces->firstWhere('id', $id);
            if (isset($skippedIdSet[$id])) {
                $skipped->push($piece);
            } else {
                $completed->push($piece);
            }
        }

        // Remaining pieces not in this session, starting from newIndex
        $rest = collect();
        for ($i = 0; $i < $total; $i++) {
            $piece = $activePieces[($newIndex + $i) % $total];
            if (!isset($assignedIdSet[$piece->id])) {
                $rest->push($piece);
            }
        }

        // New order: completed, then skipped, then rest
        $reordered = $completed->concat($skipped)->concat($rest);
        $newRotationIndex = $completed->count();

        DB::transaction(function () use ($user, $reordered, $newRotationIndex) {
            foreach ($reordered as $i => $piece) {
                $piece->update(['sort_order' => $i + 1]);
            }

            $user->update(['rotation_index' => $newRotationIndex]);
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
