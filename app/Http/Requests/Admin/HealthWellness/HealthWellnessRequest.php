<?php

namespace App\Http\Requests\Admin\HealthWellness;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class HealthWellnessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'providerName' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'featured' => 'nullable|boolean',
             'category_id' => 'required|exists:categories,id',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'directionLink' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'duration' => 'nullable|array',
            'duration.value' => 'nullable|integer|min:1',
            'duration.unit' => 'nullable|string|in:hours,days,weeks,months',
            'price' => 'nullable|array',
            'price.hasPrice' => 'nullable|boolean',
            'price.originalPrice' => 'nullable|numeric|min:0|required_if:price.hasPrice,true',
            'price.amount' => 'nullable|numeric|min:0|required_if:price.hasPrice,true',
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

    public function messages(): array
    {
        return [
            'time.days.*.endTime.after_or_equal' => 'End time must be after or equal to start time.',
            'category_id.exists' => 'The selected category is invalid or not of type health_wellness.',
            'price.originalPrice.required_if' => 'Original price is required when hasPrice is true.',
            'price.amount.required_if' => 'Amount is required when hasPrice is true.',
            'duration.unit.in' => 'Duration unit must be one of: hours, days, weeks, months.',
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
