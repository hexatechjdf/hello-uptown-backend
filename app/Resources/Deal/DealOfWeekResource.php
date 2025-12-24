<?php

namespace App\Resources\Deal;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DealOfWeekResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'slug' => Str::slug($this->title),
            'title' => $this->title,
            'businessName' => $this->business?->business_name,
            'businessLogo' => $this->business?->logo ?? null,
            'businessDescription' => $this->business?->description,
            'businessPhone' => $this->business?->phone,
            'businessWebsite' => $this->business?->website,

            'description' => $this->long_description,
            'image' => $this->image,
            'storeImage' => $this->business?->cover_image ?? null,
            'category' => $this->category?->name ?? null,
            'originalPrice' => $this->original_price,
            'discountedPrice' => $this->original_price - $this->discount,
            'expiryDate' => $this->valid_until,
            'redemptionCount' => $this->redemptions_count ?? 0,
            'terms' => $this->terms_conditions ? array_map('trim', explode("\n", $this->terms_conditions)) : [],
            'isPopular' => (bool) $this->is_featured,
        ];
    }
}
