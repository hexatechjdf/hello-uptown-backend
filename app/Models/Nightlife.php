<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Nightlife extends Model
{
    protected $fillable = [
        'title',
        'venue_name',
        'phone',
        'description',
        'image',
        'slug',
        'featured',
        'category_id',
        'tags',
        'time',
        'price',
        'location',
        'direction_link',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'tags' => 'array',
        'time' => 'array',
        'price' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($nightlife) {
            $nightlife->slug = $nightlife->generateUniqueSlug();
        });

        static::updating(function ($nightlife) {
            if ($nightlife->isDirty('title')) {
                $nightlife->slug = $nightlife->generateUniqueSlug();
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getOriginalPriceAttribute()
    {
        return $this->price['originalPrice'] ?? null;
    }

    public function getAmountAttribute()
    {
        return $this->price['amount'] ?? null;
    }

    public function getDiscountPercentageAttribute()
    {
        return $this->price['discountPercentage'] ?? null;
    }

    public function getHasPriceAttribute()
    {
        return $this->price['hasPrice'] ?? false;
    }
}
