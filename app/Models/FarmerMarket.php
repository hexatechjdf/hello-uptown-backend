<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmerMarket extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'heading',
        'description',
        'image',
        'available_vendors',
        'specialization',
        'features',
        'price',
        'address',
        'direction_link',
        'latitude',
        'longitude',
        'website',
        'ticket_link',
        'schedule',
        'next_market_date',
        'featured',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'schedule' => 'array',
        'featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'next_market_date' => 'date',
        'price' => 'decimal:2',
    ];
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
}
