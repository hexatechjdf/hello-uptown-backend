<?php

namespace App\Resources\Admin\Dining;

use Illuminate\Http\Resources\Json\JsonResource;

class DiningResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'description' => $this->description,
            'imageUrl' => $this->imageUrl,
            'tags' => $this->tags,
            'contactNumber' => $this->contact_number,
            'price' => $this->price,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'date' => $this->date?->format('Y-m-d'),
            'day' => $this->day,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'status' => $this->status,
            'createdAt' => $this->created_at->toDateTimeString(),
            'updatedAt' => $this->updated_at->toDateTimeString(),
        ];
    }
}
