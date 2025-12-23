<?php

namespace App\Resources\Website\AllPages\ArtFair;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ArtFairResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'title'       => $this->heading,
            'description' => $this->description,
            'image'       => $this->image ?? null,

            'date' => $this->event_date
                ? Carbon::parse($this->event_date)->format('l, F d, Y')
                : null,

            'time' => $this->start_time
                ? $this->start_time . ($this->end_time ? ' - ' . $this->end_time : '')
                : null,

            'location'    => $this->address,
            'artistCount' => $this->available_artist,
            'isFeatured'  => (bool) $this->featured,

            'categories'  => $this->art_categories ?? [],
            'features'    => $this->event_features ?? [],

            'admission' => $this->admission_type === 'free'
                ? 'Free'
                : ($this->admission_amount ? '$' . $this->admission_amount : null),

            'daysAway' => $this->event_date
                ? Carbon::now()->diffInDays(Carbon::parse($this->event_date), false)
                : null,
        ];
    }
}
