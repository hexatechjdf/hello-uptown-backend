<?php

namespace App\Resources\Admin\FarmerMarket;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmerMarketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->category_id,
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'description' => $this->description,
            'image' => $this->image ?? null,
            'availableVendors' => $this->available_vendors,
            'specialization' => $this->specialization,
            'features' => $this->features,
            'address' => $this->address,
            'directionLink' => $this->direction_link,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'website' => $this->website,
            'schedule' => $this->schedule,
            'nextMarketDate' => $this->next_market_date,
            'featured' => $this->featured,
            'status' => $this->status,
            'createdAt' => $this->created_at,
        ];
    }
}
