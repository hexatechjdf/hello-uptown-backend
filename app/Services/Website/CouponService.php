<?php

namespace App\Services\Website;

use App\Models\Coupon;
use App\Scopes\BusinessScope;

class CouponService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Coupon();
    }

    /**
     * Get featured coupons
     */
    public function getFeaturedCoupons($limit = 8)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular coupons
     */
    public function getPopularCoupons($limit = 10)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->orderBy('used_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get coupons by business
     */
    public function getByBusiness($businessId, $limit = 10)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('business_id', $businessId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search coupons
     */
    public function search($keyword, $perPage = 12)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('short_description', 'like', "%{$keyword}%")
                  ->orWhere('coupon_code', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Validate coupon code
     */
    public function validateCoupon($couponCode)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('coupon_code', $couponCode)
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();
    }

    /**
     * Get related coupons
     */
    public function getRelatedCoupons($coupon, $limit = 6)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('id', '!=', $coupon->id)
            ->where('is_active', true)
            ->where(function($q) use ($coupon) {
                $q->where('category_id', $coupon->category_id)
                  ->orWhere('business_id', $coupon->business_id);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $model = Coupon::withoutGlobalScope(BusinessScope::class);

        return [
            'total' => $model->where('is_active', true)->count(),
            'expiring_soon' => $model->where('is_active', true)
                ->whereBetween('valid_until', [now(), now()->addDays(7)])
                ->count(),
            'new_this_week' => $model->where('is_active', true)
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->count(),
            'featured' => $model->where('is_active', true)
                ->where('is_featured', true)
                ->count(),
        ];
    }
}
