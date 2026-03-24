<?php

namespace Database\Seeders;

use App\Models\Piece;
use App\Models\User;
use Illuminate\Database\Seeder;

class PieceSeeder extends Seeder
{
    public function run(): void
    {
        $joey = User::where('email', 'joey@test.com')->first();
        $user1 = User::where('email', 'user1@test.com')->first();
        $user2 = User::where('email', 'user2@test.com')->first();

        // Joey's pieces
        Piece::insert([
            [
                'user_id' => $joey->id,
                'title' => 'Clair de Lune',
                'composer' => 'Debussy',
                'status' => 'Polishing',
                'reference_links' => json_encode(['https://youtube.com/watch?v=CvFH_6DNRCY']),
                'notes' => 'Focus on the arpeggiated section in the middle. Keep the tempo fluid.',
                'sheet_music_paths' => null,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'Nocturne Op. 9 No. 2',
                'composer' => 'Chopin',
                'status' => 'Learning',
                'reference_links' => json_encode(['https://youtube.com/watch?v=9E6b3swbnWg']),
                'notes' => 'Work on the ornaments in measures 12-16.',
                'sheet_music_paths' => null,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'Gymnopédie No. 1',
                'composer' => 'Satie',
                'status' => 'Mastered',
                'reference_links' => null,
                'notes' => null,
                'sheet_music_paths' => null,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'Prelude in C Major',
                'composer' => 'Bach',
                'status' => 'Mastered',
                'reference_links' => json_encode(['https://youtube.com/watch?v=PXMVkQ70I88']),
                'notes' => 'BWV 846. Good warm-up piece.',
                'sheet_music_paths' => null,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'Moonlight Sonata, Mvt. 1',
                'composer' => 'Beethoven',
                'status' => 'Learning',
                'reference_links' => null,
                'notes' => 'Keep the triplets even. Pedal carefully.',
                'sheet_music_paths' => null,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'Ballade No. 1 in G minor',
                'composer' => 'Chopin',
                'status' => 'Learning',
                'reference_links' => json_encode(['https://youtube.com/watch?v=nW5po_Z7YEs', 'https://youtube.com/watch?v=Ce8p0VcFrog']),
                'notes' => 'Tackling the coda section separately. The presto is brutal.',
                'sheet_music_paths' => null,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'Liebestraum No. 3',
                'composer' => 'Liszt',
                'status' => 'Shelved',
                'reference_links' => null,
                'notes' => 'Will come back to this after building more octave technique.',
                'sheet_music_paths' => null,
                'sort_order' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'Rêverie',
                'composer' => 'Debussy',
                'status' => 'Want to Learn',
                'reference_links' => json_encode(['https://youtube.com/watch?v=QRjllL-MP0U']),
                'notes' => null,
                'sheet_music_paths' => null,
                'sort_order' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $joey->id,
                'title' => 'La Campanella',
                'composer' => 'Liszt',
                'status' => 'Want to Learn',
                'reference_links' => null,
                'notes' => 'Long-term goal. Need to work up to this.',
                'sheet_music_paths' => null,
                'sort_order' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // User1's pieces
        Piece::insert([
            [
                'user_id' => $user1->id,
                'title' => 'Für Elise',
                'composer' => 'Beethoven',
                'status' => 'Mastered',
                'reference_links' => null,
                'notes' => null,
                'sheet_music_paths' => null,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Maple Leaf Rag',
                'composer' => 'Joplin',
                'status' => 'Polishing',
                'reference_links' => null,
                'notes' => 'Left hand stride pattern needs to be more relaxed.',
                'sheet_music_paths' => null,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Arabesque No. 1',
                'composer' => 'Debussy',
                'status' => 'Learning',
                'reference_links' => json_encode(['https://youtube.com/watch?v=Yh36PaE-Pf0']),
                'notes' => null,
                'sheet_music_paths' => null,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // User2's pieces
        Piece::insert([
            [
                'user_id' => $user2->id,
                'title' => 'Waltz in A minor',
                'composer' => 'Chopin',
                'status' => 'Learning',
                'reference_links' => null,
                'notes' => 'B.150 posthumous. Short but tricky middle section.',
                'sheet_music_paths' => null,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user2->id,
                'title' => 'River Flows in You',
                'composer' => 'Yiruma',
                'status' => 'Mastered',
                'reference_links' => null,
                'notes' => null,
                'sheet_music_paths' => null,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
