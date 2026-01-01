<?php

namespace App\Resources\Admin\HappyHour;

use Illuminate\Http\Resources\Json\JsonResource;

class HappyHourResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'imageUrl' => $this->image,
            'address' => $this->address,
            'phone' => $this->phone,
            'slug' => $this->slug,
            'featured' => (bool) $this->featured,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug
            ] : null,
            'category_id' => $this->category_id,
            'openHours' => $this->open_hours ?? [],
            'deals' => $this->deals ?? [],
            'specialOffer' => $this->special_offer,
            'directionLink' => $this->direction_link,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d\TH:i:s.v\Z'),
            'updatedAt' => $this->updated_at->format('Y-m-d\TH:i:s.v\Z'),
        ];
    }
}
