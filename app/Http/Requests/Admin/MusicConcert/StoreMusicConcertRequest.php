<?php
use Illuminate\Foundation\Http\FormRequest;

class StoreMusicConcertRequest extends FormRequest
{
    public function rules()
    {
        return [
            'main_heading' => 'required|string|max:255',
            'sub_heading' => 'nullable|string|max:255',
            'event_description' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'available_attendees' => 'nullable|integer|min:1',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'place_id' => 'nullable|string',
            'website' => 'nullable|url',
            'status' => 'required|in:draft,scheduled,active,expired',
            'event_date' => 'nullable|date',
        ];
    }
}
?>