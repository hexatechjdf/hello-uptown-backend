<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Porchfest extends Model
{
    protected $fillable = [
        'title',
        'artist',
        'description',
        'image',
        'slug',
        'is_featured',
        'direction_link',
        'attendees',
        'available_seats',
        'genre',
        'event_features',
        'time',
        'location',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'genre'          => 'array',
        'event_features' => 'array',
        'time'           => 'array',
        'latitude'       => 'decimal:7',
        'longitude'      => 'decimal:7',
        'is_featured'    => 'boolean',
        'available_seats' => 'integer',
        'attendees'      => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($porchfest) {
            $porchfest->slug = $porchfest->generateUniqueSlug();
        });

        static::updating(function ($porchfest) {
            if ($porchfest->isDirty('title')) {
                $porchfest->slug = $porchfest->generateUniqueSlug();
            }
        });
    }

    protected function generateUniqueSlug()
    {
        $slug = Str::slug($this->title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
