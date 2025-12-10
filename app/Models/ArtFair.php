<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtFair extends Model
{
    protected $fillable = [
        'heading',
        'description',
        'image',
        'available_artist',
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
        'art_categories'  => 'array',
        'event_features'  => 'array',
        'event_date'      => 'date',
        'latitude'        => 'decimal:7',
        'longitude'       => 'decimal:7',
        'admission_amount' => 'decimal:2',
    ];
}
