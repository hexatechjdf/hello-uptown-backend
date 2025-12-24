<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HappyHour extends Model
{
    protected $fillable = [
        'heading',
        'image',
        'happy_hours_deals',
        'actual_price',
        'discounted_price',
        'special_offer_text',
        'address',
        'latitude',
        'longitude',
        'contact_number',
        'date',
        'day',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'happy_hours_deals' => 'array',
        'actual_price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'date' => 'date',
    ];

}
