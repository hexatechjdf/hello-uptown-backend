<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HappyHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading' => 'required|string|max:255',
            'image' => 'nullable|file|image|max:4096',
            'happy_hours_deals' => 'nullable|array',
            'happy_hours_deals.*' => 'string|max:255',
            'actual_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'special_offer_text' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'contact_number' => 'nullable|string|max:20',
            'date' => 'nullable|date',
            'day' => 'nullable|string|max:20',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:active,draft,expired',
        ];
    }
}
