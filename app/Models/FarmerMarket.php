<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerMarket extends Model
{
    protected $fillable = [
        'heading',
        'subheading',
        'description',
        'image',
        'available_vendors',
        'tags',
        'sub_tags',
        'address',
        'latitude',
        'longitude',
        'website',
        'map_meta',
        'date',
        'day',
        'start_time',
        'end_time',
        'featured',
        'status',
    ];

    protected $casts = [
        'tags' => 'array',
        'sub_tags' => 'array',
        'map_meta' => 'array',
        'featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'date' => 'date',
    ];
}
