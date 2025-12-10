<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthWellnessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Protected by admin middleware
    }

    public function rules(): array
    {
        return [
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:4096',
            'main_tags' => 'nullable|array',
            'main_tags.*' => 'string|max:50',
            'header_tags' => 'nullable|array',
            'header_tags.*' => 'string|max:50',
            'actual_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'date' => 'nullable|date',
            'day' => 'nullable|string|max:20',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:active,draft,expired',
        ];
    }
}
