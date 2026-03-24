<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PieceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'composer' => $this->composer,
            'status' => $this->status,
            'reference_links' => $this->reference_links,
            'notes' => $this->notes,
            'sheet_music_paths' => $this->sheet_music_paths,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
