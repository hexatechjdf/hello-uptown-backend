<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
     protected $fillable = [
        'heading',
        'subheading',
        'description',
        'image',
        'available_attendees',
        'address',
        'latitude',
        'longitude',
        'website',
        'date',
        'day',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'available_attendees' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'date' => 'date',
    ];

}
