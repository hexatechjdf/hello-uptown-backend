<?php

namespace App\Resources\Website\AllPages\HappyHour;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HappyHourResource extends JsonResource
{
    public function toArray($request)
    {
        $activeDays = $this->getActiveDays($this->open_hours);
        $happyHourTimes = $this->formatHappyHourTimes($this->open_hours);
        $nextHappyHour = $this->getNextHappyHour($this->open_hours);
        return [
            'id' => (string) $this->id,
            'name' => $this->title,
            'slug' => $this->slug,
            'image' => $this->image ?? null,
            'address' => $this->address,
            'phone' => $this->phone,
            'happyHourTimes' => $happyHourTimes,
            'happyHourDeals' => $this->deals ?? [],
            'activeDays' => $activeDays,
            'specialOffer' => $this->special_offer,
            'nextHappyHour' => $nextHappyHour,
            'isFeatured' => (bool) $this->featured,
            'directionLink' => $this->direction_link,
            'openHours' => $this->open_hours ?? [],
        ];
    }

    private function getActiveDays($openHours): array
    {
        if (!is_array($openHours)) {
            return [];
        }

        $activeDays = [];
        foreach ($openHours as $hour) {
            if (isset($hour['isActive']) && $hour['isActive'] === true && isset($hour['day'])) {
                $activeDays[] = $hour['day'];
            }
        }

        return $activeDays;
    }

    private function formatHappyHourTimes($openHours): string
    {
        if (!is_array($openHours)) {
            return '';
        }

        $times = [];
        foreach ($openHours as $hour) {
            if (isset($hour['isActive']) && $hour['isActive'] === true &&
                isset($hour['startTime']) && isset($hour['endTime'])) {
                $time = Carbon::parse($hour['startTime'])->format('g:i A') .
                        ' - ' .
                        Carbon::parse($hour['endTime'])->format('g:i A');
                $times[] = $time;
            }
        }

        return !empty($times) ? implode(', ', array_unique($times)) : '';
    }

    private function getNextHappyHour($openHours): ?string
    {
        if (!is_array($openHours)) {
            return null;
        }

        $today = Carbon::now()->format('l');

        foreach ($openHours as $hour) {
            if (isset($hour['day']) && $hour['day'] === $today &&
                isset($hour['isActive']) && $hour['isActive'] === true) {
                return Carbon::now()->format('Y-m-d');
            }
        }

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $currentDayIndex = array_search($today, $daysOfWeek);

        for ($i = 1; $i <= 7; $i++) {
            $nextDayIndex = ($currentDayIndex + $i) % 7;
            $nextDay = $daysOfWeek[$nextDayIndex];

            foreach ($openHours as $hour) {
                if (isset($hour['day']) && $hour['day'] === $nextDay &&
                    isset($hour['isActive']) && $hour['isActive'] === true) {
                    return Carbon::now()->addDays($i)->format('Y-m-d');
                }
            }
        }

        return null;
    }
}
