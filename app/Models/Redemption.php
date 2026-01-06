<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    protected $fillable = [
        'customer_id',
        'business_id',
        'type',        // 'deal' or 'coupon'
        'parent_id',   // coupon_id or deal_id
        'redeemed_at',
        'discount_amount',
        'status',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'discount_amount' => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Relationship to get the coupon (when type is 'coupon')
    public function coupon()
    {
        // Remove the where('type', 'coupon') clause since coupons table doesn't have 'type'
        return $this->belongsTo(Coupon::class, 'parent_id');
    }

    // Relationship to get the deal (when type is 'deal')
    public function deal()
    {
        // Remove the where('type', 'deal') clause since deals table doesn't have 'type'
        return $this->belongsTo(Deal::class, 'parent_id');
    }

    // Dynamic relationship to get the parent item based on type
    public function parent()
    {
        if ($this->type === 'coupon') {
            return $this->belongsTo(Coupon::class, 'parent_id');
        } else {
            return $this->belongsTo(Deal::class, 'parent_id');
        }
    }

    // Accessor to get the parent model instance
    public function getItemAttribute()
    {
        if ($this->type === 'coupon') {
            return $this->coupon;
        } else {
            return $this->deal;
        }
    }

    // Scope for filtering by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope for filtering by parent_id with type
    public function scopeWhereParent($query, $type, $parentId)
    {
        return $query->where('type', $type)->where('parent_id', $parentId);
    }

    // Scope for coupons only
    public function scopeCoupons($query)
    {
        return $query->where('type', 'coupon');
    }

    // Scope for deals only
    public function scopeDeals($query)
    {
        return $query->where('type', 'deal');
    }

    // Check if redemption is for a coupon
    public function isCoupon()
    {
        return $this->type === 'coupon';
    }

    // Check if redemption is for a deal
    public function isDeal()
    {
        return $this->type === 'deal';
    }

    // Get the item ID (alias for parent_id for clarity)
    public function getItemIdAttribute()
    {
        return $this->parent_id;
    }

    // Get coupon_id if type is coupon
    public function getCouponIdAttribute()
    {
        return $this->type === 'coupon' ? $this->parent_id : null;
    }

    // Get deal_id if type is deal
    public function getDealIdAttribute()
    {
        return $this->type === 'deal' ? $this->parent_id : null;
    }

    // Status check methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
