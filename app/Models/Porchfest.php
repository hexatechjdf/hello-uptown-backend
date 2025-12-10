<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Porchfest extends Model
{
    protected $fillable = [
        'heading',
        'subheading_primary',
        'subheading_secondary',
        'description',
        'image',
        'available_seats',
        'categories',
        'features',
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
        'categories' => 'array',
        'features'   => 'array',
        'event_date' => 'date',
    ];
}
