<?php

namespace App\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicConcertResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'mainHeading' => $this->main_heading,
            'artist'      => $this->artist,
            'description' => $this->event_description,
            'imageUrl'    => $this->image,
            'location'       => $this->address,
            'directionLink' => $this->direction_link,
            'time'      => $this->time_json,
            'eventDate'=> $this->event_date,
            'attendees' => $this->available_attendees,
            'price'     => $this->price,
            'websiteLink' => $this->website,
            'ticketLink'  => $this->ticket_link,
            'status'   => $this->status,
            'featured' => $this->featured,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
