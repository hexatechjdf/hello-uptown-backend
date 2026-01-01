<?php

namespace App\Resources\Website\AllPages\Porchfest;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PorchfestResource extends JsonResource
{
    public function toArray($request)
    {
        // Format time for display
        $formattedTime = $this->formatTimeDisplay($this->time);

        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'title'       => $this->title,
            'artistName'  => $this->artist,
             'category' =>  $this->category?->name ?? null,
            'description' => $this->description,
            'image'       => $this->image ?? null,
            'time'        => $formattedTime,
            'location'    => $this->location,
            'seatCount'   => $this->available_seats,
            'genres'      => $this->genre ?? [],
            'attendees'   => $this->attendees,
            'features'    => $this->event_features ?? [],
            'isFeatured'  => (bool) $this->is_featured,
            'directionLink' => $this->direction_link,
            'timeSlots'   => $this->time ?? null,
        ];
    }

    private function formatTimeDisplay($timeData): string
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
}
