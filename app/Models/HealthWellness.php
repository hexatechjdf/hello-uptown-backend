<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HealthWellness extends Model
{
    protected $fillable = [
        'title',
        'provider_name',
        'description',
        'image',
        'slug',
        'featured',
        'category_id',
        'features',
        'time',
        'duration',
        'price',
        'location',
        'direction_link',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'time' => 'array',
        'duration' => 'array',
        'price' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($healthWellness) {
            $healthWellness->slug = $healthWellness->generateUniqueSlug();
        });

        static::updating(function ($healthWellness) {
            if ($healthWellness->isDirty('title')) {
                $healthWellness->slug = $healthWellness->generateUniqueSlug();
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

    public function getHasPriceAttribute()
    {
        return $this->price['hasPrice'] ?? false;
    }
}
