<?php
namespace App\Resources\Admin\Porchfest;

use Illuminate\Http\Resources\Json\JsonResource;

class PorchfestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'description' => $this->description,
            'image' => $this->image,
            'slug' => $this->slug,
            'isFeatured' => (bool) $this->is_featured,
            'directionLink' => $this->direction_link,
            'attendees' => $this->attendees,
            'availableSeats' => $this->available_seats,
            'genre' => $this->genre ?? [],
            'eventFeatures' => $this->event_features ?? [],
            'time' => $this->time ?? null,
            'location' => $this->location,
            'lat' => $this->latitude,
            'lng' => $this->longitude,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
