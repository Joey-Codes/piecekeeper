<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
