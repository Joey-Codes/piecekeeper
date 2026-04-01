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
            'has_password' => $this->password !== null,
            'has_google' => $this->google_id !== null,
            'rotation_index' => $this->rotation_index,
            'rollover_skipped' => (bool) $this->rollover_skipped,
            'auto_end_session' => (bool) $this->auto_end_session,
            'pieces_per_session' => $this->pieces_per_session,
            'practice_frequency' => $this->practice_frequency,
            'timezone' => $this->timezone,
        ];
    }
}
