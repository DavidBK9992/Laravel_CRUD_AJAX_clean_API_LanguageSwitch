<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'post_title' => 'sometimes|string|max:255',
            'post_description' => 'sometimes|string',
            'post_status' => 'sometimes|string|in:active,inactive',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048', // optional
        ];
    }
}

