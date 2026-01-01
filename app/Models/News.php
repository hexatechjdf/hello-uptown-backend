<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title',
        'description',
        'author',
        'image',
        'slug',
        'featured',
        'category_id',
        'article_url',
        'published_at',
        'status',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            $news->slug = $news->generateUniqueSlug();
        });

        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $news->slug = $news->generateUniqueSlug();
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

    public function getPublishedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d\TH:i:s.v\Z') : null;
    }
}
