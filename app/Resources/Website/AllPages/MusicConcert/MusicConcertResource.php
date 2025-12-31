<?php

namespace App\Resources\Website\AllPages\MusicConcert;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MusicConcertResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => Str::slug($this->main_heading),
            'title' => $this->main_heading,
            'venue' => $this->address,
            'description' => $this->event_description,
            'image' => $this->image ?? null,
            'location' => $this->address,
            'time' => $this->event_date,
            'attendance' => $this->available_attendees,
            'price' => $this->price,
            'category' =>  $this->category?->name ?? null,
            'websiteUrl' => $this->website,
            'directionsUrl' => $this->direction_link,
            'ticketUrl' => $this->ticket_link,
        ];
    }
}
