<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthWellnessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'description' => $this->description,
            'imageUrl' => $this->imageUrl,
            'mainTags' => $this->main_tags,
            'headerTags' => $this->header_tags,
            'actualPrice' => $this->actual_price,
            'discountedPrice' => $this->discounted_price,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'date' => $this->date?->format('Y-m-d'),
            'day' => $this->day,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'status' => $this->status,
            'createdAt' => $this->created_at->toDateTimeString(),
            'updatedAt' => $this->updated_at->toDateTimeString(),
        ];
    }
}
