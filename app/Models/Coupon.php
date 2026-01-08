<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BusinessScope;

class Coupon extends Model
{
    protected $fillable = [
        'business_id',
        'title',
        'coupon_code',
        'short_description',
        'image',
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
    protected static function booted()
    {
        static::addGlobalScope(new BusinessScope);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function redemptions()
    {
        return $this->hasMany(Redemption::class, 'parent_id')
            ->where('type', 'coupon');
    }
}
