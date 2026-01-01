<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Dining extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'slug',
        'is_featured',
        'direction_link',
        'phone',
        'cuisine_types',
        'time',
        'price_range',
        'location',
        'latitude',
        'longitude',
        'category_id',
        'status',
    ];

    protected $casts = [
        'cuisine_types' => 'array',
        'time' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dining) {
            $dining->slug = $dining->generateUniqueSlug();
        });

        static::updating(function ($dining) {
            if ($dining->isDirty('title')) {
                $dining->slug = $dining->generateUniqueSlug();
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

    // Relationship to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
