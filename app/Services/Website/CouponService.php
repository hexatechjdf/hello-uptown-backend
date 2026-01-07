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

    // public function redeemCoupon(array $data)
    // {
    //     if ($data['type'] === 'coupon') {
    //         $model = Coupon::with('business')->withoutGlobalScope(BusinessScope::class)->find($data['parent_id']);
    //         $type = 'coupon';
    //         $itemName = 'coupon';
    //     } else {
    //         $model = Deal::with('business')->withoutGlobalScope(BusinessScope::class)->find($data['parent_id']);
    //         $type = 'deal';
    //         $itemName = 'deal';
    //     }

    //     if (!$model) {
    //         return [
    //             'message' => ucfirst($itemName) . ' not found'
    //         ];
    //     }
    //     if (!$model->business) {
    //         return [
    //             'message' => ucfirst($itemName) . ' business not found'
    //         ];
    //     }

    //     if (!$model->is_active) {
    //         return [
    //             'message' => ucfirst($itemName) . ' is not active'
    //         ];
    //     }
    //     $business = $model->business;
    //     $today = Carbon::today();
    //     // Check validity dates
    //     if ($today->lt(Carbon::parse($model->valid_from))) {
    //         return [
    //             'message' => ucfirst($itemName) . ' is not yet valid',
    //             'valid_from' => $model->valid_from,
    //             'current_date' => $today->toDateString()
    //         ];
    //     }

    //     if ($today->gt(Carbon::parse($model->valid_until))) {
    //         return [
    //             'message' => ucfirst($itemName) . ' has expired',
    //             'valid_until' => $model->valid_until,
    //             'current_date' => $today->toDateString()
    //         ];
    //     }
    //     // Verify customer exists
    //     $customer = Customer::find($data['customer_id']);
    //     if (!$customer) {
    //         return [
    //             'message' => 'Customer not found'
    //         ];
    //     }

    //     // Calculate distance
    //     $distanceInMeters = $this->calculateDistance(
    //         $data['latitude'],
    //         $data['longitude'],
    //         $business->latitude,
    //         $business->longitude
    //     );

    //     if ($distanceInMeters > $business->redemption_radius) {
    //         return [
    //             'message' => 'You are outside the redemption range',
    //             'distance_in_meters' => round($distanceInMeters, 2),
    //             'allowed_radius' => $business->redemption_radius
    //         ];
    //     }

    //     // Check usage limit
    //     $usedCount = Redemption::where('customer_id', $customer->id)
    //         ->where('type', $type)
    //         ->where('parent_id', $model->id)
    //         ->count();

    //     if ($model->usage_limit_per_user > 0 && $usedCount >= $model->usage_limit_per_user) {
    //         return [
    //             'message' => 'You have reached the maximum redemption limit for this ' . $itemName,
    //             'used_count' => $usedCount,
    //             'usage_limit' => $model->usage_limit_per_user
    //         ];
    //     }

    //     // Calculate discount amount
    //     $discountAmount = 0;
    //     if ($type === 'coupon') {
    //         if ($model->discount_type === 'percentage') {
    //             $discountAmount = ($model->discount_value / 100) * $model->minimum_spend;
    //         } else {
    //             $discountAmount = $model->discount_value;
    //         }
    //     } else {
    //         // Handle deal discount calculation if different
    //         $discountAmount = $model->discount_amount ?? 0;
    //     }

    //     // Create redemption record
    //     $redemption = Redemption::create([
    //         'customer_id' => $customer->id,
    //         'business_id' => $business->id,
    //         'type'        => $type,
    //         'parent_id'   => $model->id,
    //         'redeemed_at' => Carbon::now(),
    //         'discount_amount' => $discountAmount,
    //         'status' => 'pending'
    //     ]);

    //     return [
    //         'success' => true,
    //         'redemption_id' => $redemption->id,
    //         'coupon' => $model,
    //         'distance_in_meters' => round($distanceInMeters, 2),
    //         'discount_amount' => $discountAmount,
    //         'message' => ucfirst($itemName) . ' redeemed successfully'
    //     ];
    // }

    public function redeemCoupon(array $data)
    {
        if ($data['type'] === 'coupon') {
            $model = Coupon::with('business')->withoutGlobalScope(BusinessScope::class)->find($data['parent_id']);
            $type = 'coupon';
            $itemName = 'coupon';
        } else {
            $model = Deal::with('business')->withoutGlobalScope(BusinessScope::class)->find($data['parent_id']);
            $type = 'deal';
            $itemName = 'deal';
        }

        if (!$model) {
            return [
                'error' => true, // Added this key
                'message' => ucfirst($itemName) . ' not found'
            ];
        }

        if (!$model->business) {
            return [
                'error' => true, // Added this key
                'message' => ucfirst($itemName) . ' business not found'
            ];
        }

        if (!$model->is_active) {
            return [
                'error' => true, // Added this key
                'message' => ucfirst($itemName) . ' is not active'
            ];
        }

        $business = $model->business;
        $today = Carbon::today();

        // Check validity dates
        if ($today->lt(Carbon::parse($model->valid_from))) {
            return [
                'error' => true, // Added this key
                'message' => ucfirst($itemName) . ' is not yet valid',
                'valid_from' => $model->valid_from,
                'current_date' => $today->toDateString()
            ];
        }

        if ($today->gt(Carbon::parse($model->valid_until))) {
            return [
                'error' => true, // Added this key
                'message' => ucfirst($itemName) . ' has expired',
                'valid_until' => $model->valid_until,
                'current_date' => $today->toDateString()
            ];
        }

        // Verify customer exists
        $customer = Customer::find($data['customer_id']);
        if (!$customer) {
            return [
                'error' => true, // Added this key
                'message' => 'Customer not found'
            ];
        }

        // Calculate distance
        $distanceInMeters = $this->calculateDistance(
            $data['latitude'],
            $data['longitude'],
            $business->latitude,
            $business->longitude
        );

        if ($distanceInMeters > $business->redemption_radius) {
            return [
                'error' => true, // Added this key
                'message' => 'You are outside the redemption range',
                'distance_in_meters' => round($distanceInMeters, 2),
                'allowed_radius' => $business->redemption_radius
            ];
        }

        // Check usage limit
        $usedCount = Redemption::where('customer_id', $customer->id)
            ->where('type', $type)
            ->where('parent_id', $model->id)
            ->count();

        if ($model->usage_limit_per_user > 0 && $usedCount >= $model->usage_limit_per_user) {
            return [
                'error' => true, // Added this key
                'message' => 'You have reached the maximum redemption limit for this ' . $itemName,
                'used_count' => $usedCount,
                'usage_limit' => $model->usage_limit_per_user
            ];
        }

        // Calculate discount amount
        $discountAmount = 0;
        if ($type === 'coupon') {
            if ($model->discount_type === 'percentage') {
                $discountAmount = ($model->discount_value / 100) * $model->minimum_spend;
            } else {
                $discountAmount = $model->discount_value;
            }
        } else {
            // Handle deal discount calculation if different
            $discountAmount = $model->discount_amount ?? 0;
        }

        // Create redemption record
        $redemption = Redemption::create([
            'customer_id' => $customer->id,
            'business_id' => $business->id,
            'type'        => $type,
            'parent_id'   => $model->id,
            'redeemed_at' => Carbon::now(),
            'discount_amount' => $discountAmount,
            'status' => 'pending'
        ]);

        return [
            'success' => true,
            'redemption_id' => $redemption->id,
            'coupon' => $model,
            'distance_in_meters' => round($distanceInMeters, 2),
            'discount_amount' => $discountAmount,
            'message' => ucfirst($itemName) . ' redeemed successfully'
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
