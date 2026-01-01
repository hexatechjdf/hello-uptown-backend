<?php

namespace App\Resources\Website\AllPages\Dining;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DiningResource extends JsonResource
{
    public function toArray($request)
    {
        $formattedHours = $this->formatHours($this->time);
        return [
            'id'          => (string) $this->id,
            'name'        => $this->title,
            'priceRange'  => $this->price_range,
            'description' => $this->description,
            'address'     => $this->location,
            'hours'       => $formattedHours,
            'phone'       => $this->phone,
            'image'       => $this->image ?? null,
            'features'    => $this->cuisine_types ?? [],
            'category'    => $this->category ? $this->category->name : 'dining',
            'slug'        => $this->slug,
            'isFeatured'  => (bool) $this->is_featured,
            'directionLink' => $this->direction_link,
            'timeSlots'   => $this->time ?? null,
        ];
    }

    private function formatHours($timeData): string
    {
        if (!$timeData || !isset($timeData['type'])) {
            return '';
        }
        if ($timeData['type'] === 'days') {
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
        elseif ($timeData['type'] === 'startend') {
            $formatted = '';
            if (isset($timeData['startDate'])) {
                $formatted .= Carbon::parse($timeData['startDate'])->format('M d, Y');
            }
            if (isset($timeData['startTime'])) {
                $formatted .= ' ' . Carbon::parse($timeData['startTime'])->format('g:i A');
            }
            if (isset($timeData['endDate']) || isset($timeData['endTime'])) {
                $formatted .= ' to ';
            }
            if (isset($timeData['endDate'])) {
                $formatted .= Carbon::parse($timeData['endDate'])->format('M d, Y');
            }
            if (isset($timeData['endTime'])) {
                $formatted .= ' ' . Carbon::parse($timeData['endTime'])->format('g:i A');
            }
            return $formatted;
        }
        return '';
    }
}
