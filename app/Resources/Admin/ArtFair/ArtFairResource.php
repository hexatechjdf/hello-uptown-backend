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
            'slug'             => $this->slug,
            'featured'         => (bool) $this->featured,
            'directionLink'    => $this->direction_link,
            'availableArtist'  => $this->available_artist,
            'artistCount'      => $this->artist_count,
            'artCategories'    => $this->art_categories ?? [],
            'eventFeatures'    => $this->event_features ?? [],
            'admissionType'    => $this->admission_type,
            'admissionAmount'  => $this->admission_amount,
            'address'          => $this->address,
            'latitude'         => $this->latitude,
            'longitude'        => $this->longitude,
            'eventDate'        => $this->event_date?->format('Y-m-d'),
            'day'              => $this->day,
            'startTime'        => $this->start_time,
            'endTime'          => $this->end_time,
            'status'           => $this->status,
            'createdAt'        => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt'        => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
