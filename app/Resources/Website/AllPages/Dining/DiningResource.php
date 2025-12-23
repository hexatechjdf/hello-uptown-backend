<?php

namespace App\Resources\Website\AllPages\Dining;

use Illuminate\Http\Resources\Json\JsonResource;

class DiningResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => (string) $this->id,
            'name'        => $this->heading,
            'priceRange'  => $this->price,
            'description' => $this->description,
            'address'     => $this->address,
            'hours' => $this->start_time ? $this->start_time . ($this->end_time ? ' - ' . $this->end_time : ''): null,

            'phone' => $this->contact_number,

            'image' => $this->image ?? null,

            'features' => $this->tags ?? [],

            'category' => $this->category ?? 'dining',
        ];
    }
}
