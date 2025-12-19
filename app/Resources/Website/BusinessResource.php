<?php

namespace App\Resources\Website;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'businessName'        => $this->business_name,
            'coverImage'          => $this->cover_image,
            'logo'                => $this->logo,
            'stats' => [
                    [
                        'value' => $this->slider_text1,
                        'label' => $this->slider_text1_value,
                    ],
                    [
                        'value' => $this->slider_text2,
                        'label' => $this->slider_text2_value,
                    ],
                    [
                        'value' => $this->slider_text3,
                        'label' => $this->slider_text3_value,
                    ],
                ],
            'sliderTagline' => $this->slider_tagline,
            'sliderSectionText'                => $this->slider_section_text,
            'sliderHeadingOne'                => $this->slider_heading_one,
            'sliderSubheading'                => $this->slider_subheading,
            'sliderShortDescription'                => $this->slider_short_description,
            'sliderImage'                => $this->slider_image,
            'imageOverlayHeading'                => $this->image_overlay_heading,
            'imageOverlayHeading2'                => $this->image_overlay_heading2,
            'slug'                => $this->slug,
            'shortDescription'    => $this->short_description,
            'longDescription'     => $this->long_description,
            'description'         => $this->description ?? $this->short_description,
            'category'            => $this->category ?? null,
            'tags'                => $this->tags ?? [],
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
        ];
    }
}
