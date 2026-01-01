<?php

namespace App\Resources\Admin\NightLife;

use Illuminate\Http\Resources\Json\JsonResource;

class NightlifeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'venueName' => $this->venue_name,
            'description' => $this->description,
            'image' => $this->image,
            'slug' => $this->slug,
            'featured' => (bool) $this->featured,
           'category' =>$this->category->name ?? null,
            'category_id' => $this->category_id,
            'tags' => $this->tags ?? [],
            'phone' => $this->phone,
            'location' => $this->location,
            'directionLink' => $this->direction_link,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'price' => $this->price ?? [
                'hasPrice' => false,
                'originalPrice' => null,
                'amount' => null,
                'discountPercentage' => null
            ],
            'time' => $this->time ?? null,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d\TH:i:s.v\Z'),
            'updatedAt' => $this->updated_at->format('Y-m-d\TH:i:s.v\Z'),
        ];
    }
}
