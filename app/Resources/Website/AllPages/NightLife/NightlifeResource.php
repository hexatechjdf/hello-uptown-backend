<?php

namespace App\Resources\Website\AllPages\NightLife;

use Illuminate\Http\Resources\Json\JsonResource;

class NightlifeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'slug' => $this->slug,
            'name'       => $this->heading,
            'category'   => $this->subheading,
            'dealTitle'  => $this->header_tags,
            'description' => $this->description,
            'image' => $this->image?? null,
            'hours' => $this->start_time ? $this->start_time . ($this->end_time ? ' - ' . $this->end_time : '') : null,
            'location' => $this->address,
            'originalPrice'   => $this->actual_price,
            'discountedPrice' => $this->discounted_price,
            'features' => $this->main_tags ?? [],
            'eventBadge' => $this->event_badge ?? null,
            'activeDays' => $this->active_days ?? [],
        ];
    }
}
