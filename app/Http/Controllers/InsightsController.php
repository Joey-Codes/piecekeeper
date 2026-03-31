<?php

namespace App\Http\Controllers;

use App\Services\InsightsService;
use Illuminate\Http\Request;

class InsightsController extends Controller
{
    public function index(Request $request, InsightsService $service)
    {
        return response()->json(
            $service->getStats($request->user())
        );
    }

    public function calendar(Request $request, InsightsService $service)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        return response()->json([
            'dates' => $service->getCalendar(
                $request->user(),
                $request->integer('year'),
                $request->integer('month'),
            ),
        ]);
    }

    public function session(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $session = $request->user()->sessions()
            ->whereDate('date', $request->date)
            ->with('pieces')
            ->first();

        if (! $session) {
            abort(404, 'No session found for this date.');
        }

        return response()->json([
            'session' => [
                'id' => $session->id,
                'date' => $session->date->toDateString(),
                'pieces_assigned' => $session->pieces_assigned,
                'pieces_completed' => $session->pieces_completed,
                'duration_seconds' => $session->duration_seconds,
                'pieces' => $session->pieces->map(fn ($piece) => [
                    'id' => $piece->id,
                    'title' => $piece->title,
                    'composer' => $piece->composer,
                    'completed' => (bool) $piece->pivot->completed,
                ]),
            ],
        ]);
    }
}
