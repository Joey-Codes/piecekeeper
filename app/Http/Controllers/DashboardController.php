<?php

namespace App\Http\Controllers;

use App\Models\PracticeSession;
use App\Services\PracticeSessionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function today(Request $request, PracticeSessionService $service)
    {
        $session = $service->getOrCreateForToday($request->user());

        return response()->json([
            'session' => $this->formatSession($session),
        ]);
    }

    public function togglePiece(Request $request, PracticeSession $session)
    {
        if ($session->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'piece_id' => 'required|integer',
        ]);

        $pivot = $session->pieces()->where('piece_id', $request->piece_id)->first();
        if (!$pivot) {
            abort(404, 'Piece not in this session');
        }

        $newCompleted = !$pivot->pivot->completed;
        $session->pieces()->updateExistingPivot($request->piece_id, [
            'completed' => $newCompleted,
        ]);

        // Update denormalized count
        $session->update([
            'pieces_completed' => $session->pieces()->wherePivot('completed', true)->count(),
        ]);

        return response()->json([
            'piece_id' => (int) $request->piece_id,
            'completed' => $newCompleted,
            'pieces_completed' => $session->fresh()->pieces_completed,
        ]);
    }

    public function finish(Request $request, PracticeSession $session)
    {
        if ($session->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'duration_seconds' => 'required|integer|min:0',
        ]);

        $session->update([
            'duration_seconds' => $request->duration_seconds,
        ]);

        return response()->json([
            'session' => $this->formatSession($session->fresh()->load('pieces')),
        ]);
    }

    public function update(Request $request, PracticeSession $session)
    {
        if ($session->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'duration_seconds' => 'required|integer|min:0|max:86400',
            'pieces' => 'required|array',
            'pieces.*.id' => 'required|integer',
            'pieces.*.completed' => 'required|boolean',
        ]);

        $session->update([
            'duration_seconds' => $data['duration_seconds'],
        ]);

        foreach ($data['pieces'] as $pieceData) {
            $session->pieces()->updateExistingPivot($pieceData['id'], [
                'completed' => $pieceData['completed'],
            ]);
        }

        $session->update([
            'pieces_completed' => $session->pieces()->wherePivot('completed', true)->count(),
        ]);

        return response()->json([
            'session' => $this->formatSession($session->fresh()->load('pieces')),
        ]);
    }

    public function history(Request $request)
    {
        $sessions = $request->user()->sessions()
            ->whereDate('date', '>=', Carbon::today($request->user()->timezone ?? 'UTC')->subDays(6))
            ->with('pieces')
            ->orderBy('date')
            ->get();

        return response()->json([
            'sessions' => $sessions->map(fn ($s) => $this->formatSession($s)),
        ]);
    }

    protected function formatSession(PracticeSession $session): array
    {
        return [
            'id' => $session->id,
            'date' => $session->date->toDateString(),
            'pieces_assigned' => $session->pieces_assigned,
            'pieces_completed' => $session->pieces_completed,
            'duration_seconds' => $session->duration_seconds,
            'pieces' => $session->pieces->map(fn ($piece) => [
                'id' => $piece->id,
                'title' => $piece->title,
                'composer' => $piece->composer,
                'status' => $piece->status,
                'notes' => $piece->notes,
                'reference_links' => $piece->reference_links,
                'sheet_music_paths' => $piece->sheet_music_paths,
                'position' => $piece->pivot->position,
                'completed' => (bool) $piece->pivot->completed,
            ]),
        ];
    }
}
