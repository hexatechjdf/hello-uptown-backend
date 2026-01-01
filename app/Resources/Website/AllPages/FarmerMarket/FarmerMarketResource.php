<?php

namespace App\Resources\Website\AllPages\FarmerMarket;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmerMarketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug ?? null,
            'title' => $this->heading,
            'venue' => $this->address,
            'description' => $this->description,
            'image' => $this->image ?? null,
            'location' => $this->address,
            'time' => $this->schedule ?? null,
            'attendance' => $this->available_vendors,
            'price' => $this->price,
            'category' => $this->category?->name ?? 'farmer-market',
            'websiteUrl' => $this->website,
            'directionsUrl' => $this->direction_link ?? null,
            'ticketUrl' => $this->ticket_link,
        ];
    }
}
