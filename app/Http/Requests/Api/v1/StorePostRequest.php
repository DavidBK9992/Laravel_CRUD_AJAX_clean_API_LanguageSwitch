<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'post_title' => 'required|string|max:255',
            'post_description' => 'required|string',
            'post_status' => 'required|string|in:active,inactive',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048', // optional
        ];
    }
}
