<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MusicConcert extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_heading',
        'sub_heading',
        'event_description',
        'image',
        'available_attendees',
        'address',
        'latitude',
        'longitude',
        'place_id',
        'website',
        'status',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'date',
        'latitude'   => 'decimal:7',
        'longitude'  => 'decimal:7',
    ];
}
