<?php

namespace App\Services\Website;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Redemption;
use App\Scopes\BusinessScope;
use Carbon\Carbon;
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

    public function redeemCoupon(array $data)
    {
        $coupon = Coupon::with('business')
            ->withoutGlobalScope(BusinessScope::class)
            ->find($data['coupon_id']);

        if (!$coupon || !$coupon->business || !$coupon->is_active) {
            return null;
        }

        $business = $coupon->business;
        $today = Carbon::today();
        if ($today->lt(Carbon::parse($coupon->valid_from)) || $today->gt(Carbon::parse($coupon->valid_until))) {
            return null;
        }

        $customer = Customer::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'  => $data['name'],
                'phone' => $data['phone'],
            ]
        );

        $distanceInMeters = $this->calculateDistance(
            $data['latitude'],
            $data['longitude'],
            $business->latitude,
            $business->longitude
        );

        if ($distanceInMeters > $business->redemption_radius) {
            return [
                'error' => 'outside_radius',
                'distance_in_meters' => round($distanceInMeters, 2),
                'allowed_radius' => $business->redemption_radius
            ];
        }

        $usedCount = Redemption::where('customer_id', $customer->id)
            ->where('coupon_id', $coupon->id)
            ->count();

        if ($coupon->usage_limit_per_user > 0 && $usedCount >= $coupon->usage_limit_per_user) {
            return null;
        }

        $discountAmount = $coupon->discount_type === 'percentage' ? ($coupon->discount_value / 100) * $coupon->minimum_spend : $coupon->discount_value;

        $redemption = Redemption::create([
            'customer_id' => $customer->id,
            'coupon_id'   => $coupon->id,
            'business_id' => $business->id,
            'redeemed_at' => Carbon::now(),
            'discount_amount' => $discountAmount,
            'status' => 'pending'
        ]);

        return [
            'redemption_id' => $redemption->id,
            'coupon' => $coupon,
            'distance_in_meters' => round($distanceInMeters, 2)
        ];
    }

    /**
     * Calculate distance between two coordinates in meters
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000;

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $latDiff = $lat2 - $lat1;
        $lonDiff = $lon2 - $lon1;

        $a = sin($latDiff / 2) ** 2
            + cos($lat1) * cos($lat2)
            * sin($lonDiff / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

}
