<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MusicConcert extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_heading',
        'category_id',
        'artist',
        'event_description',
        'image',
        'available_attendees',
        'price',
        'address',
        'direction_link',
        'latitude',
        'longitude',
        'website',
        'ticket_link',
        'time_json',
        'status',
        'featured',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'date',
        'time_json'  => 'array',
        'featured'   => 'boolean',
        'latitude'   => 'decimal:7',
        'longitude'  => 'decimal:7',
    ];

     public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
