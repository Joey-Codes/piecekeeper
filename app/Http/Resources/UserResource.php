<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'rotation_index' => $this->rotation_index,
            'rollover_skipped' => (bool) $this->rollover_skipped,
            'pieces_per_session' => $this->pieces_per_session,
            'practice_frequency' => $this->practice_frequency,
            'timezone' => $this->timezone,
        ];
    }
}
