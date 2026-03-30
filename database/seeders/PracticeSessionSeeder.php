<?php

namespace Database\Seeders;

use App\Models\PracticeSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PracticeSessionSeeder extends Seeder
{
    public function run(): void
    {
        $joey = User::where('email', 'joey@test.com')->first();
        $pieces = $joey->pieces()
            ->whereNotNull('sort_order')
            ->orderBy('sort_order')
            ->get();

        $total = $pieces->count();
        $perSession = $joey->pieces_per_session;
        $rotationIndex = 0;

        // 4 sessions over the past week: Mon, Tue, Thu, Sat
        $today = Carbon::today($joey->timezone ?? 'America/Los_Angeles');
        $dates = [
            $today->copy()->subDays(6), // last Thursday
            $today->copy()->subDays(5), // last Friday
            $today->copy()->subDays(3), // Sunday
            $today->copy()->subDays(1), // yesterday (Tuesday)
        ];

        $sessionData = [
            ['duration' => 1320, 'completed' => [true, true, true]],    // 22 min, all done
            ['duration' => 960,  'completed' => [true, true, false]],   // 16 min, 2/3
            ['duration' => 1800, 'completed' => [true, true, true]],    // 30 min, all done
            ['duration' => 1080, 'completed' => [true, false, true]],   // 18 min, 2/3
        ];

        foreach ($dates as $i => $date) {
            $assigned = collect();
            for ($j = 0; $j < $perSession; $j++) {
                $assigned->push($pieces[($rotationIndex + $j) % $total]);
            }
            $rotationIndex = ($rotationIndex + $perSession) % $total;

            $completedFlags = $sessionData[$i]['completed'];
            $completedCount = count(array_filter($completedFlags));

            $session = PracticeSession::create([
                'user_id' => $joey->id,
                'date' => $date,
                'pieces_assigned' => $perSession,
                'pieces_completed' => $completedCount,
                'duration_seconds' => $sessionData[$i]['duration'],
            ]);

            foreach ($assigned as $pos => $piece) {
                $session->pieces()->attach($piece->id, [
                    'position' => $pos + 1,
                    'completed' => $completedFlags[$pos],
                ]);
            }
        }

        // Update Joey's rotation_index to reflect where we left off
        $joey->update(['rotation_index' => $rotationIndex]);
    }
}
