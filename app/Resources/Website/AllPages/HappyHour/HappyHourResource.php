<?php

namespace App\Resources\Website\AllPages\HappyHour;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class HappyHourResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->heading,
            'slug' => Str::slug($this->heading),
            'image' => $this->image ?? null,
            'address' => $this->address,
            'phone' => $this->contact_number,
            'happyHourTimes' => trim($this->start_time . ' - ' . $this->end_time),
            'happyHourDeals' => $this->happy_hours_deals ?? [],
            'activeDays' => $this->day? array_map('trim', explode(',', $this->day)): [],
            'specialOffer' => $this->special_offer_text,
            'nextHappyHour' => $this->date?->format('Y-m-d'),
        ];
    }
}
