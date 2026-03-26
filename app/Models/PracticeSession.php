<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PracticeSession extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'pieces_assigned',
        'pieces_completed',
        'duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pieces(): BelongsToMany
    {
        return $this->belongsToMany(Piece::class, 'practice_session_pieces')
            ->withPivot(['position', 'completed'])
            ->orderByPivot('position');
    }
}
