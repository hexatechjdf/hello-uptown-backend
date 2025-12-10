<?php

namespace App\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicConcertResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'mainHeading' => $this->main_heading,
            'subHeading' => $this->sub_heading,
            'description' => $this->event_description,
            'imageUrl' => $this->image ? asset('storage/' . $this->image) : null,
            'availableAttendees' => $this->available_attendees,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'website' => $this->website,
            'status' => $this->status,
            'eventDate' => $this->event_date,
            'createdAt' => $this->created_at,
        ];
    }
}
