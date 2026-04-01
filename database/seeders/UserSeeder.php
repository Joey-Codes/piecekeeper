<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Joey',
                'email' => 'joey@test.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'pieces_per_session' => 3,
                'practice_frequency' => 'every_day',
                'timezone' => 'America/Los_Angeles',
                'rotation_index' => 0,
                'rollover_skipped' => 0,
                'auto_end_session' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User1',
                'email' => 'user1@test.com',
                'email_verified_at' => null,
                'password' => bcrypt('12345678'),
                'pieces_per_session' => 5,
                'practice_frequency' => 'weekdays',
                'timezone' => 'America/Los_Angeles',
                'rotation_index' => 0,
                'rollover_skipped' => 1,
                'auto_end_session' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User2',
                'email' => 'user2@test.com',
                'email_verified_at' => null,
                'password' => bcrypt('12345678'),
                'pieces_per_session' => 2,
                'practice_frequency' => 'every_3_days',
                'timezone' => 'America/Los_Angeles',
                'rotation_index' => 0,
                'rollover_skipped' => 1,
                'auto_end_session' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
