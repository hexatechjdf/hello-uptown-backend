<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    protected $fillable = [
        'customer_id',
        'coupon_id',
        'business_id',
        'deal_id',
        'redeemed_at',
        'discount_amount',
        'status',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
