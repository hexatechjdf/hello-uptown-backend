<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArtFair extends Model
{
    protected $fillable = [
        'heading',
        'description',
        'image',
        'slug',
        'featured',
        'direction_link',
        'available_artist',
        'artist_count',
        'art_categories',
        'event_features',
        'admission_type',
        'admission_amount',
        'address',
        'latitude',
        'longitude',
        'event_date',
        'day',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'art_categories'    => 'array',
        'event_features'    => 'array',
        'event_date'        => 'date',
        'latitude'          => 'decimal:7',
        'longitude'         => 'decimal:7',
        'admission_amount'  => 'decimal:2',
        'featured'          => 'boolean',
        'available_artist'  => 'integer',
        'artist_count'      => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artFair) {
            $artFair->slug = $artFair->generateUniqueSlug();
        });

        static::updating(function ($artFair) {
            if ($artFair->isDirty('heading')) {
                $artFair->slug = $artFair->generateUniqueSlug();
            }
        });
    }

    protected function generateUniqueSlug()
    {
        $slug = Str::slug($this->heading);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
