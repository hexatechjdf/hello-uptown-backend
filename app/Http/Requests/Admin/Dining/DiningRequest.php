<?php

namespace App\Http\Requests\Admin\Dining;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DiningRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'isFeatured' => 'nullable|boolean',
            'directionLink' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'cuisineTypes' => 'nullable|array',
            'cuisineTypes.*' => 'string|max:50',
            'price' => 'nullable|string|in:low,moderate,high,luxury',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'time' => 'nullable|array',
            'time.type' => 'nullable|string|in:days,startend',
            // For days type
            'time.days' => 'nullable|array|required_if:time.type,days',
            'time.days.*.day' => 'nullable|string',
            'time.days.*.startTime' => 'nullable|date_format:H:i',
            'time.days.*.endTime' => 'nullable|date_format:H:i|after_or_equal:time.days.*.startTime',
            // For startend type
            'time.startDate' => 'nullable|date|required_if:time.type,startend',
            'time.startTime' => 'nullable|date_format:H:i|required_if:time.type,startend',
            'time.endDate' => 'nullable|date|required_if:time.type,startend',
            'time.endTime' => 'nullable|date_format:H:i|required_if:time.type,startend',
            'status' => 'required|in:active,draft,expired,inactive',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422));
    }
}
