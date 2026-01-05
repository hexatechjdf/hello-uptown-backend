<?php

namespace App\Http\Requests\Admin\ArtFair;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArtFairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading'           => 'required|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|url',
            'featured'          => 'nullable|boolean',
            'direction_link'    => 'nullable|url',
            'available_artist'  => 'nullable|integer|min:0',
            'artist_count'      => 'nullable|integer|min:0',
            'art_categories'    => 'nullable|json',
            'art_categories.*'  => 'nullable|string',
            'event_features'    => 'nullable|json',
            'event_features.*'  => 'nullable|string',
            'admission_type'    => 'required|in:free,paid',
            'admission_amount'  => 'required_if:admission_type,paid|nullable|numeric|min:0',
            'address'           => 'nullable|string',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'event_date'        => 'required|date',
            'day'               => 'nullable|string',
            'start_time'        => 'nullable|date_format:H:i',
            'end_time'          => 'nullable|date_format:H:i|after_or_equal:start_time',
            'status'            => 'required|in:draft,scheduled,active,expired',
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after_or_equal' => 'End time must be after or equal to start time.',
            'admission_amount.required_if' => 'Admission amount is required when admission type is paid.',
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
