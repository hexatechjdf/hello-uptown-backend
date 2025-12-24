<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nightlife extends Model
{
     protected $fillable = [
        'heading',
        'subheading',
        'description',
        'image',
        'main_tags',
        'header_tags',
        'actual_price',
        'discounted_price',
        'address',
        'latitude',
        'longitude',
        'date',
        'day',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'main_tags' => 'array',
        'header_tags' => 'array',
        'actual_price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'date' => 'date',
    ];

}
