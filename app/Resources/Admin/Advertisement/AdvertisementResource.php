<?php

namespace App\Resources\Admin\Advertisement;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'imageUrl'   => $this->image_url,
            'actionLink' => $this->action_link,
            'startDate'  => $this->start_date?->format('Y-m-d'),
            'endDate'    => $this->end_date?->format('Y-m-d'),
            'status'     => $this->status,
            'createdAt'  => $this->created_at->toDateTimeString(),
            'updatedAt'  => $this->updated_at->toDateTimeString(),
        ];
    }
}
