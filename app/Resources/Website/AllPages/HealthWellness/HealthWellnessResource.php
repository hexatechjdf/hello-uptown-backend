<?php

namespace App\Resources\Website\AllPages\HealthWellness;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HealthWellnessResource extends JsonResource
{
    public function toArray($request)
    {
        $priceData = $this->price ?? [];
        $hasPrice = $priceData['hasPrice'] ?? false;
        $originalPrice = $priceData['originalPrice'] ?? null;
        $amount = $priceData['amount'] ?? null;
        $durationData = $this->duration ?? [];
        $durationValue = $durationData['value'] ?? null;
        $durationUnit = $durationData['unit'] ?? null;
        $daysLeft = $this->calculateDaysLeft($this->time);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'businessName' => $this->provider_name,
            'description' => $this->description,
            'image' => $this->image ?? null,
            'category' => $this->category ? $this->category->name : null,
            'location' => $this->location,
            'daysLeft' => $daysLeft,
            'originalPrice' => $originalPrice,
            'discountedPrice' => $amount,
            'features' => $this->features ?? [],
            'directionsUrl' => $this->direction_link,
            'duration' => $durationValue && $durationUnit ? "$durationValue $durationUnit" : null,
            'isFeatured' => (bool) $this->featured,
            'timeSlots' => $this->time ?? null,
        ];
    }

    private function calculateDaysLeft($timeData): ?int
    {
        if (!$timeData || !isset($timeData['type'])) {
            return null;
        }

        if ($timeData['type'] === 'startend' && isset($timeData['endDate'])) {
            return Carbon::now()->diffInDays(Carbon::parse($timeData['endDate']), false);
        }

        if ($timeData['type'] === 'days' && isset($timeData['days'][0]['startDate'])) {
            return Carbon::now()->diffInDays(Carbon::parse($timeData['days'][0]['startDate']), false);
        }

        return null;
    }
}
