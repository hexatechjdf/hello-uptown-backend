<?php

namespace App\Http\Requests\Admin\MusicConcert;

use Illuminate\Foundation\Http\FormRequest;

class MusicConcertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'main_heading' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'artist_name' => 'nullable|string|max:255',
            'event_description' => 'required|string',
            'image' => 'nullable|url',
            'attendee_limit' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
            'direction_link' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'website' => 'nullable|url',
            'ticket_link' => 'nullable|url',
            'time_slots' => 'nullable|array',
            'event_date' => 'nullable|date',
            'status' => 'required|in:draft,active,scheduled,expired',
            'featured' => 'boolean',
        ];

        // Add unique rule for update
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['title'] = 'sometimes|required|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The main heading is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid.',
            'event_date.date' => 'Please enter a valid date.',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
        ];
    }

    public function prepareForValidation()
    {
        // Convert featured to boolean if present
        if ($this->has('featured')) {
            $this->merge([
                'featured' => filter_var($this->featured, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}
