<?php

namespace App\Resources\Website\AllPages\Porchfest;

use Illuminate\Http\Resources\Json\JsonResource;

class PorchfestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'title'       => $this->heading,
            'artistName'  => $this->subheading_primary,
            'hostedBy'    => $this->subheading_secondary,
            'description' => $this->description,
            'image'       => $this->image ?? null,
            'category' => is_array($this->categories)
                ? ($this->categories[0] ?? null)
                : $this->categories,
            'time' => $this->start_time
                ? $this->start_time . ($this->end_time ? ' - ' . $this->end_time : '')
                : null,
            'location'  => $this->address,
            'seatCount' => $this->available_seats,
            'genres' => $this->categories ?? [],
        ];
    }
}
