<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'slug',
        'short_description',
        'long_description',
        'description',
        'category',
        'tags',
        'logo',
        'cover_image',
        'address',
        'latitude',
        'longitude',
        'redemption_radius',
        'phone',
        'email',
        'website',
        'opening_hours',
        'facebook_link',
        'instagram_link',
        'twitter_link',
        'slider_tagline',
        'slider_section_text',
        'slider_heading_one',
        'slider_subheading',
        'slider_short_description',
        'slider_image',
        'image_overlay_heading',
        'image_overlay_heading2',
        'send_new_deals',
        'status',
    ];

    protected $casts = [
        'tags' => 'array',
        'send_new_deals' => 'boolean',
        'status' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'redemption_radius' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors for front-end friendly URLs
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset($this->logo) : null;
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover_image ? asset($this->cover_image) : null;
    }

    // Slider settings as array (optional)
    public function getSliderSettingsAttribute()
    {
        return [
            'tagline'             => $this->slider_tagline,
            'sectionSliderText'   => $this->slider_section_text,
            'headingOne'          => $this->slider_heading_one,
            'subheading'          => $this->slider_subheading,
            'shortDescription'    => $this->slider_short_description,
            'imageUrl'            => $this->slider_image ? asset($this->slider_image) : null,
            'imageOverlayHeading' => $this->image_overlay_heading,
            'imageOverlayHeading2'=> $this->image_overlay_heading2,
            'sliderText1'         => $this->slider_text1,
            'sliderText1Value'    => $this->slider_text1_value,
            'sliderText2'         => $this->slider_text2,
            'sliderText2Value'    => $this->slider_text2_value,
            'sliderText3'         => $this->slider_text3,
            'sliderText3Value'    => $this->slider_text3_value,
        ];
    }

    // Notification settings as array
    public function getNotificationSettingsAttribute()
    {
        return [
            'sendNewDealsToCustomers' => $this->send_new_deals,
        ];
    }
}
