<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SheetMusicUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files' => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf|max:20480',
        ];
    }
}
