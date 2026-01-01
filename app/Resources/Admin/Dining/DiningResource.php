<?php

namespace App\Resources\Admin\Dining;

use Illuminate\Http\Resources\Json\JsonResource;

class DiningResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'slug' => $this->slug,
            'isFeatured' => (bool) $this->is_featured,
            'directionLink' => $this->direction_link,
            'phone' => $this->phone,
            'cuisineTypes' => $this->cuisine_types ?? [],
            'price' => $this->price_range,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'category' =>$this->category->name ?? null,
            'category_id' => $this->category_id,
            'time' => $this->time ?? null,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
