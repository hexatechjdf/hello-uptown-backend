<?php

namespace App\Resources\Website\AllPages\News;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'description' => $this->description,
            'imageUrl' => $this->image, // Changed from imageUrl to match request field 'image'
            'availableAttendees' => $this->available_attendees, // Added from request
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'website' => $this->website, // Added from request
            'date' => $this->date?->format('Y-m-d'),
            'day' => $this->day,
            'startTime' => $this->start_time, // Formatted to match request format
            'endTime' => $this->end_time, // Formatted to match request format
            'status' => $this->status,
            'createdAt' => $this->created_at->toDateTimeString(),
            'updatedAt' => $this->updated_at->toDateTimeString(),
        ];
    }
}
