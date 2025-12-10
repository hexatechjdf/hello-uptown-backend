<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HappyHourResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'imageUrl' => $this->imageUrl,
            'happyHoursDeals' => $this->happy_hours_deals,
            'actualPrice' => $this->actual_price,
            'discountedPrice' => $this->discounted_price,
            'specialOfferText' => $this->special_offer_text,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'contactNumber' => $this->contact_number,
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
