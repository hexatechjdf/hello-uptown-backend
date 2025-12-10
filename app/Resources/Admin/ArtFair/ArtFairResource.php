<?php

namespace App\Resources\Admin\ArtFair;    

use Illuminate\Http\Resources\Json\JsonResource;

class ArtFairResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'heading'          => $this->heading,
            'description'      => $this->description,
            'image'            => $this->image ? asset($this->image) : null,
            'availableArtist'  => $this->available_artist,
            'artCategories'    => $this->art_categories,
            'eventFeatures'    => $this->event_features,
            'admissionType'    => $this->admission_type,
            'admissionAmount'  => $this->admission_amount,
            'address'          => $this->address,
            'latitude'         => $this->latitude,
            'longitude'        => $this->longitude,
            'eventDate'        => $this->event_date?->format('d/m/Y'),
            'day'              => $this->day,
            'startTime'        => $this->start_time,
            'endTime'          => $this->end_time,
            'status'           => $this->status,
            'createdAt'        => $this->created_at,
        ];
    }
}
