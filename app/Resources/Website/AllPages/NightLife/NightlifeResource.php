<?php

namespace App\Resources\Website\AllPages\Nightlife;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NightlifeResource extends JsonResource
{
    public function toArray($request)
    {
        $formattedHours = $this->formatHours($this->time);
        $activeDays = $this->getActiveDays($this->time);
        $priceData = $this->price ?? [];
        $hasPrice = $priceData['hasPrice'] ?? false;
        $originalPrice = $priceData['originalPrice'] ?? null;
        $amount = $priceData['amount'] ?? null;
        $discountPercentage = $priceData['discountPercentage'] ?? null;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->title,
            'category' => $this->category ? $this->category->name : null,
            'dealTitle' => $this->venue_name,
            'description' => $this->description,
            'image' => $this->image ? asset($this->image) : null,
            'hours' => $formattedHours,
            'location' => $this->location,
            'originalPrice' => $originalPrice,
            'discountedPrice' => $amount,
            'features' => $this->tags ?? [],
            'eventBadge' => $hasPrice && $discountPercentage ? $discountPercentage . '% OFF' : null,
            'activeDays' => $activeDays,
            'phone' => $this->phone,
            'directionLink' => $this->direction_link,
            'isFeatured' => (bool) $this->featured,
            'timeSlots' => $this->time ?? null,
        ];
    }

    private function formatHours($timeData): string
    {
        if (!$timeData || !isset($timeData['type']) || $timeData['type'] !== 'days') {
            return '';
        }

        $days = $timeData['days'] ?? [];
        if (empty($days)) {
            return '';
        }

        $formattedSlots = [];
        foreach ($days as $day) {
            $slot = $day['day'] ?? '';
            if (isset($day['startTime'])) {
                $slot .= ' ' . Carbon::parse($day['startTime'])->format('g:i A');
            }
            if (isset($day['endTime'])) {
                $slot .= ' - ' . Carbon::parse($day['endTime'])->format('g:i A');
            }
            if ($slot) {
                $formattedSlots[] = $slot;
            }
        }

        return implode(', ', $formattedSlots);
    }

    private function getActiveDays($timeData): array
    {
        if (!$timeData || !isset($timeData['type']) || $timeData['type'] !== 'days') {
            return [];
        }

        $days = $timeData['days'] ?? [];
        $activeDays = [];

        foreach ($days as $day) {
            if (isset($day['day'])) {
                $activeDays[] = $day['day'];
            }
        }

        return array_unique($activeDays);
    }
}
