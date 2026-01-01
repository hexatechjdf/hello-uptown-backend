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
            'image'       => $this->image ? asset($this->image) : null,

            'date' => $this->event_date
                ? Carbon::parse($this->event_date)->format('l, F d, Y')
                : null,

            'time' => $this->start_time
                ? Carbon::parse($this->start_time)->format('g:i A') .
                  ($this->end_time ? ' - ' . Carbon::parse($this->end_time)->format('g:i A') : '')
                : null,

            'location'    => $this->address,
            'artistCount' => $this->artist_count ?? $this->available_artist ?? 0,
            'isFeatured'  => (bool) $this->featured,

            'categories'  => $this->art_categories ?? [],
            'features'    => $this->event_features ?? [],

            'admission' => $this->admission_type === 'free'
                ? 'Free'
                : ($this->admission_amount ? '$' . number_format($this->admission_amount, 2) : null),

            'daysAway' => $this->event_date
                ? Carbon::now()->diffInDays(Carbon::parse($this->event_date), false)
                : null,

            'directionLink' => $this->direction_link,
        ];
    }
}
