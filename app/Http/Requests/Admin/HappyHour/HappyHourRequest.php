<?php

namespace App\Http\Requests\Admin\HappyHour;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class HappyHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'imageUrl' => 'nullable|url',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'featured' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
            'openHours' => 'nullable|array',
            'openHours.*.day' => 'nullable|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'openHours.*.isActive' => 'nullable|boolean',
            'openHours.*.startTime' => 'nullable|date_format:H:i|required_if:openHours.*.isActive,true',
            'openHours.*.endTime' => 'nullable|date_format:H:i|required_if:openHours.*.isActive,true|after_or_equal:openHours.*.startTime',
            'deals' => 'nullable|array',
            'deals.*.name' => 'nullable|string|max:255',
            'deals.*.regularPrice' => 'nullable|numeric|min:0',
            'deals.*.happyHourPrice' => 'nullable|numeric|min:0',
            'specialOffer' => 'nullable|string|max:255',
            'directionLink' => 'nullable|url',
            'status' => 'required|in:active,draft,expired,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'openHours.*.endTime.after_or_equal' => 'End time must be after or equal to start time.',
            'openHours.*.startTime.required_if' => 'Start time is required when day is active.',
            'openHours.*.endTime.required_if' => 'End time is required when day is active.',
            'category_id.exists' => 'The selected category is invalid or not of type happy_hours.',
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
