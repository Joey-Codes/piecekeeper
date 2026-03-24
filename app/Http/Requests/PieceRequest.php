<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PieceRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredOrSometimes = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'title' => "$requiredOrSometimes|string|max:255",
            'composer' => 'nullable|string|max:255',
            'status' => "$requiredOrSometimes|string|max:50",
            'reference_links' => 'nullable|array',
            'reference_links.*' => 'string|max:2048',
            'notes' => 'nullable|string',
            'sheet_music_paths' => 'nullable|array',
            'sheet_music_paths.*' => 'string|max:2048',
        ];
    }
}
