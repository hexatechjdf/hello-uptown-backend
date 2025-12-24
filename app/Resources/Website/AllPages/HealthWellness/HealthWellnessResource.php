<?php

namespace App\Resources\Website\AllPages\HealthWellness;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HealthWellnessResource extends JsonResource
{
    public function toArray($request)
    {
        // Handle features/header_tags - check if it's array or string
        $features = [];

        if ($this->header_tags) {
            if (is_string($this->header_tags)) {
                // If it's a string, explode by comma
                $features = array_map('trim', explode(',', $this->header_tags));
            } elseif (is_array($this->header_tags)) {
                // If it's already an array, use it directly
                $features = $this->header_tags;
                // Optionally trim all values if needed
                $features = array_map('trim', $features);
            }
        }
        return [
            'id' => $this->id,
            'slug' => Str::slug($this->heading),
            'title' => $this->heading,
            'businessName' => $this->subheading,
            'description' => $this->description,
            'image' => $this->image ?? null,
            'category' => $this->main_tags,
            'location' => $this->address,
            'daysLeft' => $this->date ? Carbon::now()->diffInDays(Carbon::parse($this->date), false) : null,
            'originalPrice' => $this->actual_price,
            'discountedPrice' => $this->discounted_price,
            'features' => $features,
            'directionsUrl' => $this->address,

        ];
    }
}
