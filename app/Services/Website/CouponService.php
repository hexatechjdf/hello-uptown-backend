<?php

namespace App\Services\Website;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Redemption;
use App\Models\Deal;
use App\Scopes\BusinessScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CouponService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Coupon();
    }

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

    private function getItemDetails($type, $parentId)
    {
        if ($type === 'coupon') {
            $item = Coupon::withoutGlobalScope(BusinessScope::class)
                ->with('business:id,latitude,longitude,redemption_radius,business_name')
                ->find($parentId);
            $itemName = 'coupon';
        } else {
            $item = Deal::withoutGlobalScope(BusinessScope::class)
                ->with('business:id,latitude,longitude,redemption_radius,name')
                ->find($parentId);
            $itemName = 'deal';
        }

        return ['item' => $item, 'itemName' => $itemName];
    }

    private function validateItem($item, $itemName)
    {
        if (!$item) {
            return ['error' => true, 'message' => ucfirst($itemName) . ' not found'];
        }

        if (!$item->business) {
            return ['error' => true, 'message' => ucfirst($itemName) . ' business not found'];
        }

        if (!$item->is_active) {
            return ['error' => true, 'message' => ucfirst($itemName) . ' is not active'];
        }

        $today = Carbon::today();
        $validFrom = Carbon::parse($item->valid_from);
        $validUntil = Carbon::parse($item->valid_until);

        if ($today->lt($validFrom)) {
            return [
                'error' => true,
                'message' => ucfirst($itemName) . ' is not yet valid',
                'valid_from' => $item->valid_from
            ];
        }

        if ($today->gt($validUntil)) {
            return [
                'error' => true,
                'message' => ucfirst($itemName) . ' has expired',
                'valid_until' => $item->valid_until
            ];
        }

        return ['success' => true];
    }

    private function checkUsageLimit($customerId, $type, $itemId, $usageLimit)
    {
        $redemptions = Redemption::where('customer_id', $customerId)
            ->where('type', $type)
            ->where('parent_id', $itemId)
            ->orderBy('redeemed_at', 'desc')
            ->get();

        $usedCount = $redemptions->count();
        $redemptionDates = $redemptions->pluck('redeemed_at')
            ->map(fn($d) => $d->format('Y-m-d H:i:s'))
            ->toArray();

        if ($usageLimit > 0 && $usedCount >= $usageLimit) {
            return [
                'error' => true,
                'message' => 'You have reached the maximum redemption limit',
                'used_count' => $usedCount,
                'usage_limit' => $usageLimit,
                'redemption_count' => $usedCount,
                'redemption_dates' => $redemptionDates
            ];
        }

        return [
            'success' => true,
            'used_count' => $usedCount,
            'redemption_count' => $usedCount,
            'redemption_dates' => $redemptionDates
        ];
    }

    private function calculateDiscountAmount($item, $type)
    {
        if ($type === 'coupon') {
            if ($item->discount_type === 'percentage') {
                return ($item->discount_value / 100) * $item->minimum_spend;
            }
            return $item->discount_value;
        }
        return $item->discount_amount ?? 0;
    }

    public function checkEligibility(array $data)
    {
        extract($this->getItemDetails($data['type'], $data['parent_id']));

        if (!$item) {
            return [
                'eligible' => false,
                'message' => ucfirst($itemName) . ' not found',
                'redemption_dates' => [],
                'item' => null,
            ];
        }

        $validationResult = $this->validateItem($item, $itemName);
        if (isset($validationResult['error'])) {
            $this->removeBusinessLatLng($item);
            return [
                'eligible' => false,
                'message' => $validationResult['message'],
                'redemption_dates' => [],
                'item' => $item,
            ] + (isset($validationResult['valid_from']) ? ['valid_from' => $validationResult['valid_from']] : [])
              + (isset($validationResult['valid_until']) ? ['valid_until' => $validationResult['valid_until']] : []);
        }

        $customer = Customer::find($data['customer_id']);
        if (!$customer) {
            $this->removeBusinessLatLng($item);
            return [
                'eligible' => false,
                'message' => 'Customer not found',
                'redemption_dates' => [],
                'item' => $item,
            ];
        }

        $usageCheck = $this->checkUsageLimit(
            $customer->id,
            $data['type'],
            $item->id,
            $item->usage_limit_per_user
        );

        $this->removeBusinessLatLng($item);

        if (isset($usageCheck['error'])) {
            return [
                'eligible' => false,
                'message' => $usageCheck['message'],
                'redemption_count' => $usageCheck['redemption_count'],
                'redemption_dates' => $usageCheck['redemption_dates'],
                'item' => $item,
            ];
        }

        return [
            'eligible' => true,
            'message' => 'Customer is eligible to redeem this ' . $itemName,
            'redemption_count' => $usageCheck['used_count'],
            'remaining_redemptions' => $item->usage_limit_per_user > 0
                ? ($item->usage_limit_per_user - $usageCheck['used_count'])
                : 'unlimited',
            'redemption_dates' => $usageCheck['redemption_dates'],
            'item' => $item,
        ];
    }

    public function redeemCoupon(array $data)
    {
        extract($this->getItemDetails($data['type'], $data['parent_id']));

        $validationResult = $this->validateItem($item, $itemName);
        if (isset($validationResult['error'])) {
            return ['error' => true] + $validationResult;
        }

        $customer = Customer::find($data['customer_id']);
        if (!$customer) {
            return ['error' => true, 'message' => 'Customer not found'];
        }

        $business = $item->business;
        $distanceInMeters = $this->calculateDistance(
            $data['latitude'],
            $data['longitude'],
            $business->latitude,
            $business->longitude
        );

        if ($distanceInMeters > $business->redemption_radius) {
            return [
                'error' => true,
                'message' => 'You are outside the redemption range',
                'distance_in_meters' => round($distanceInMeters, 2),
                'allowed_radius' => $business->redemption_radius
            ];
        }

        $usageCheck = $this->checkUsageLimit(
            $customer->id,
            $data['type'],
            $item->id,
            $item->usage_limit_per_user
        );

        if (isset($usageCheck['error'])) {
            return ['error' => true] + $usageCheck;
        }

        $discountAmount = $this->calculateDiscountAmount($item, $data['type']);

        $redemption = Redemption::create([
            'customer_id' => $customer->id,
            'business_id' => $business->id,
            'type'        => $data['type'],
            'parent_id'   => $item->id,
            'redeemed_at' => Carbon::now(),
            'discount_amount' => $discountAmount,
            'status' => 'pending'
        ]);

        return [
            'success' => true,
            'redemption_id' => $redemption->id,
            'coupon' => $item,
            'distance_in_meters' => round($distanceInMeters, 2),
            'discount_amount' => $discountAmount,
            'redemption_count' => $usageCheck['used_count'] + 1,
            'redemption_dates' => array_merge(
                $usageCheck['redemption_dates'],
                [Carbon::now()->format('Y-m-d H:i:s')]
            ),
            'message' => ucfirst($itemName) . ' redeemed successfully'
        ];
    }

    private function removeBusinessLatLng($item): void
    {
        if ($item && $item->business) {
            unset($item->business->latitude, $item->business->longitude);
        }
    }

    public function getFeaturedCoupons($limit = 8)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPopularCoupons($limit = 10)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->orderBy('used_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getByBusiness($businessId, $limit = 10)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('business_id', $businessId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

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

    public function validateCoupon($couponCode)
    {
        return Coupon::withoutGlobalScope(BusinessScope::class)
            ->where('coupon_code', $couponCode)
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();
    }

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
