<?php

namespace App\Services;

use App\Models\Piece;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InsightsService
{
    public function getStats(User $user): array
    {
        $tz = $user->timezone ?? 'UTC';
        $now = Carbon::now($tz);
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();

        $sessions = $user->sessions();

        // One query: lifetime totals + this week + this month sessions
        $aggregates = $sessions
            ->selectRaw('COUNT(*) as total_sessions')
            ->selectRaw('COALESCE(SUM(duration_seconds), 0) as total_seconds')
            ->selectRaw('COALESCE(SUM(CASE WHEN date >= ? THEN duration_seconds ELSE 0 END), 0) as week_seconds', [$weekStart->toDateString()])
            ->selectRaw('COUNT(CASE WHEN date >= ? THEN 1 END) as week_sessions', [$weekStart->toDateString()])
            ->first();

        // One query: piece counts by status
        $pieceCounts = $user->pieces()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalPieces = $pieceCounts->sum();
        $activePieces = $totalPieces - ($pieceCounts[Piece::STATUS_SHELVED] ?? 0);
        $masteredPieces = $pieceCounts[Piece::STATUS_MASTERED] ?? 0;

        // One query: most practiced piece (by session count)
        $mostPracticed = DB::table('practice_session_pieces')
            ->join('practice_sessions', 'practice_sessions.id', '=', 'practice_session_pieces.practice_session_id')
            ->join('pieces', 'pieces.id', '=', 'practice_session_pieces.piece_id')
            ->where('practice_sessions.user_id', $user->id)
            ->selectRaw('pieces.id, pieces.title, COUNT(*) as session_count')
            ->groupBy('pieces.id', 'pieces.title')
            ->orderByDesc('session_count')
            ->first();

        // Pieces that reached Mastered status this month
        $masteredThisMonth = $user->pieces()
            ->where('status', Piece::STATUS_MASTERED)
            ->where('updated_at', '>=', $monthStart)
            ->count();

        $totalSessions = (int) $aggregates->total_sessions;
        $totalSeconds = (int) $aggregates->total_seconds;

        return [
            'lifetime_seconds' => $totalSeconds,
            'lifetime_sessions' => $totalSessions,
            'week_seconds' => (int) $aggregates->week_seconds,
            'week_sessions' => (int) $aggregates->week_sessions,
            'total_pieces' => $totalPieces,
            'active_pieces' => $activePieces,
            'learning_pieces' => $pieceCounts[Piece::STATUS_LEARNING] ?? 0,
            'polishing_pieces' => $pieceCounts[Piece::STATUS_POLISHING] ?? 0,
            'relearning_pieces' => $pieceCounts[Piece::STATUS_RELEARNING] ?? 0,
            'mastered_pieces' => $masteredPieces,
            'mastered_this_month' => $masteredThisMonth,
            'avg_session_seconds' => $totalSessions > 0 ? (int) round($totalSeconds / $totalSessions) : 0,
            'most_practiced' => $mostPracticed ? [
                'title' => $mostPracticed->title,
                'session_count' => (int) $mostPracticed->session_count,
            ] : null,
        ];
    }

    public function getCalendar(User $user, int $year, int $month): array
    {
        $dates = $user->sessions()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->pluck('date')
            ->map(fn ($d) => $d->toDateString())
            ->values()
            ->all();

        return $dates;
    }
}
