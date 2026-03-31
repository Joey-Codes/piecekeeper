<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:50',
            'rotation_index' => 'sometimes|integer|min:0',
            'rollover_skipped' => 'sometimes|boolean',
            'pieces_per_session' => 'sometimes|integer|min:1|max:20',
            'practice_frequency' => 'sometimes|string|in:every_day,every_2_days,every_3_days,every_4_days,every_5_days,every_6_days,weekly,biweekly,monthly,weekdays,weekends',
            'timezone' => 'sometimes|string|timezone:all',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'current_password' => 'required_with:new_password|current_password',
            'new_password' => ['sometimes', 'confirmed', Password::min(8)],
        ];
    }
}
