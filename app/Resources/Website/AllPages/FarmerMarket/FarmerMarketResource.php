<?php

namespace App\Resources\Website\AllPages\FarmerMarket;
use Illuminate\Http\Resources\Json\JsonResource;
class FarmerMarketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'slug'          => $this->slug,
            'title'         => $this->heading, // or title column if exists
            'venue'         => $this->address,
            'description'   => $this->description,
            'image'         => $this->image ?? null,
            'location'      => $this->address,
            'time'          => $this->start_time
                                ? $this->start_time . ($this->end_time ? ' - ' . $this->end_time : '')
                                : null,
            'attendance'    => $this->available_vendors,
            'price'         => $this->price ?? null,
            'category'      => $this->category ?? 'farmer-market',
            'websiteUrl'    => $this->website,
            'directionsUrl' => $this->address ?? null,
            'ticketUrl'     => $this->ticket_url ?? null,
        ];
    }
}
?>
