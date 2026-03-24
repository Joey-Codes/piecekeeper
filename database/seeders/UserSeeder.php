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
                'rotation_index' => 0,
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
                'rotation_index' => 0,
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
                'rotation_index' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
