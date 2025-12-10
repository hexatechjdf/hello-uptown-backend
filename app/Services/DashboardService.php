<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Coupon;
use App\Models\Redemption;

class DashboardService
{
    public function getMetrics($businessId)
    {
        $totalDeals = Deal::where('business_id', $businessId)->count();

        $totalRedemptions = Redemption::where('business_id', $businessId)->count();

        $conversionRate = $totalDeals > 0 
            ? round(($totalRedemptions / $totalDeals) * 100, 2)
            : 0;

        return [
            'total_deals' => $totalDeals,
            'conversion_rate' => $conversionRate,
            'total_redemptions' => $totalRedemptions,
        ];
    }

    public function topCoupons($businessId)
    {
        return Coupon::where('business_id', $businessId)
            ->where('status', true)
            ->orderBy('redemption_count', 'desc')
            ->take(10)
            ->get();
    }

    public function recentRedemptions($businessId)
    {
        return Redemption::with(['coupon:id,title', 'user:id,name'])
            ->where('business_id', $businessId)
            ->orderBy('redeemed_at', 'desc')
            ->take(10)
            ->get();
    }
}
