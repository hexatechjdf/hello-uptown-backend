<?php

namespace App\Resources\Business;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{ 
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->business_name,
            'slug'                => $this->slug,
            'shortDescription'    => $this->short_description,
            'longDescription'     => $this->long_description,
            'description'         => $this->description ?? $this->short_description,
            'category'            => $this->category ?? null,
            'tags'                => $this->tags ?? [],
            'logo'                => $this->logo,
            'coverImage'          => $this->cover_image,
            'address'             => $this->address,
            'latitude'            => $this->latitude,
            'longitude'           => $this->longitude,
            'redemptionRadius'    => $this->redemption_radius ?? null,
            'phone'               => $this->phone,
            'email'               => $this->email,
            'website'             => $this->website,
            'openingHours'        => $this->opening_hours,
            'socialLinks'         => [
                'facebook'  => $this->facebook_link,
                'instagram' => $this->instagram_link,
                'twitter'   => $this->twitter_link,
            ],
            'memberSince'         => $this->created_at,
            'isActive'            => $this->status,
            'sliderSettings' => $this->sliderSettings,
            'notificationSettings' => $this->notificationSettings,
            'coverUrl' => $this->coverUrl,
            'logoUrl' => $this->logoUrl,

        ];
    }
}
