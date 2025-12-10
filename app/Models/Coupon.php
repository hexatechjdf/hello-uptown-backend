<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'business_id',
        'title',
        'coupon_code',
        'short_description',
        'long_description',
        'discount_type',
        'discount_value',
        'category_id',
        'valid_from',
        'valid_until',
        'usage_limit_per_user',
        'minimum_spend',
        'terms_conditions',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
