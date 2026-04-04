<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Piece extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'composer',
        'status',
        'reference_links',
        'notes',
        'sheet_music_paths',
        'sort_order',
    ];

    public const STATUS_LEARNING = 'Learning';
    public const STATUS_POLISHING = 'Polishing';
    public const STATUS_MASTERED = 'Mastered';
    public const STATUS_RELEARNING = 'Relearning';
    public const STATUS_SHELVED = 'Shelved';
    public const STATUS_WANT_TO_LEARN = 'Want To Learn';

    protected function casts(): array
    {
        return [
            'reference_links' => 'array',
            'sheet_music_paths' => 'array',
        ];
    }

    public function getSheetMusicAttribute(): array
    {
        return collect($this->sheet_music_paths ?? [])->map(fn ($path) => [
            'path' => $path,
            'name' => Str::after(basename($path), '_'),
            'url' => "/api/pieces/{$this->id}/sheet-music/" . basename($path),
        ])->values()->all();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function practiceSessions(): BelongsToMany
    {
        return $this->belongsToMany(PracticeSession::class, 'practice_session_pieces')
            ->withPivot(['position', 'completed']);
    }
}
