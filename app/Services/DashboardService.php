<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Coupon;
use App\Models\Redemption;
use Carbon\Carbon;
class DashboardService
{
   public function getMetrics($businessId, $duration = null)
    {
        [$from, $to] = $this->dateRange($duration);

        $totalDeals = Deal::where('business_id', $businessId)
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $totalRedemptions = Redemption::where('business_id', $businessId)
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $conversionRate = $totalDeals > 0
            ? round(($totalRedemptions / $totalDeals) * 100, 2)
            : 0;

        return [
            'total_deals'        => $totalDeals,
            'total_redemptions' => $totalRedemptions,
            'conversion_rate'   => $conversionRate,
        ];
    }

    public function topCoupons($businessId, $duration = null)
        {
            [$from, $to] = $this->dateRange($duration);

            return Coupon::where('business_id', $businessId)
                ->whereBetween('created_at', [$from, $to])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        public function recentRedemptions($businessId, $duration = null)
        {
            [$from, $to] = $this->dateRange($duration);

            return Redemption::with(['coupon:id,title', 'customer:id,name'])
                ->where('business_id', $businessId)
                ->whereBetween('redeemed_at', [$from, $to])
                ->orderBy('redeemed_at', 'desc')
                ->take(10)
                ->get();
        }
    private function dateRange($duration = null)
    {
        if ($duration) {
            return [
                Carbon::now()->subMonths($duration)->startOfDay(),
                Carbon::now()->endOfDay(),
            ];
        }
        // Default → current month
        return [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ];
    }

}
