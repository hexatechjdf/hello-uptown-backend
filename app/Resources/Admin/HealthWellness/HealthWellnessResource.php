<?php

namespace App\Resources\Admin\HealthWellness;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthWellnessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'providerName' => $this->provider_name,
            'description' => $this->description,
            'image' => $this->image,
            'slug' => $this->slug,
            'featured' => (bool) $this->featured,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug
            ] : null,
            'category_id' => $this->category_id,
            'features' => $this->features ?? [],
            'location' => $this->location,
            'directionLink' => $this->direction_link,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'duration' => $this->duration ?? [
                'value' => null,
                'unit' => null
            ],
            'price' => $this->price ?? [
                'hasPrice' => false,
                'originalPrice' => null,
                'amount' => null
            ],
            'time' => $this->time ?? null,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d\TH:i:s.v\Z'),
            'updatedAt' => $this->updated_at->format('Y-m-d\TH:i:s.v\Z'),
        ];
    }
}
