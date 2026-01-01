<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PorchfestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'isFeatured' => 'nullable|boolean',
            'directionLink' => 'nullable|url',
            'attendees' => 'nullable|integer|min:0',
            'available_seats' => 'nullable|integer|min:0',
            'genre' => 'nullable|array',
            'genre.*' => 'nullable|string',
            'eventFeatures' => 'nullable|array',
            'eventFeatures.*' => 'nullable|string',
            'time' => 'nullable|array',
            'time.type' => 'nullable|string|in:days',
            'time.days' => 'nullable|array',
            'time.days.*.day' => 'nullable|string',
            'time.days.*.startTime' => 'nullable|date_format:H:i',
            'time.days.*.endTime' => 'nullable|date_format:H:i|after_or_equal:time.days.*.startTime',
            'location' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:draft,scheduled,active,expired,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'time.days.*.endTime.after_or_equal' => 'End time must be after or equal to start time.',
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
