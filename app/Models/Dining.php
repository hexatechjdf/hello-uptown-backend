<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dining extends Model
{
    protected $fillable = [
        'heading',
        'description',
        'image',
        'tags',
        'contact_number',
        'price',
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
        'tags' => 'array',
        'price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'date' => 'date',
    ];

}
