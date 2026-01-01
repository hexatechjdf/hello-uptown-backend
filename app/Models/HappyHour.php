<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HappyHour extends Model
{
    protected $fillable = [
        'title',
        'image',
        'address',
        'phone',
        'slug',
        'featured',
        'category_id',
        'open_hours',
        'deals',
        'special_offer',
        'direction_link',
        'status',
    ];

    protected $casts = [
        'open_hours' => 'array',
        'deals' => 'array',
        'featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($happyHour) {
            $happyHour->slug = $happyHour->generateUniqueSlug();
        });

        static::updating(function ($happyHour) {
            if ($happyHour->isDirty('title')) {
                $happyHour->slug = $happyHour->generateUniqueSlug();
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
}
