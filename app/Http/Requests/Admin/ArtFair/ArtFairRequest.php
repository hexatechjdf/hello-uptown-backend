<?php

namespace App\Http\Requests\Admin\ArtFair;
use Illuminate\Foundation\Http\FormRequest;

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
            'image'             => 'nullable|image|max:4096',
            'available_artist'  => 'nullable|integer',
            'art_categories'    => 'nullable|array',
            'event_features'    => 'nullable|array',
            'admission_type'    => 'required|in:free,paid',
            'admission_amount'  => 'required_if:admission_type,paid|nullable|numeric|min:0',
            'address'           => 'nullable|string',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'event_date'        => 'required|date',
            'day'               => 'nullable|string',
            'start_time'        => 'nullable',
            'end_time'          => 'nullable',
            'status'            => 'required|in:draft,scheduled,active,expired',
        ];
    }
}
