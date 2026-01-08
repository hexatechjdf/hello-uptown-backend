<?php

namespace App\Services\Admin;

use App\Models\Business;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Redemption;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function stats(): array
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $currentMonthBusinesses = Business::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $lastMonthBusinesses = Business::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $businessPercentageChange = $this->calculatePercentageChange($lastMonthBusinesses, $currentMonthBusinesses);

        $currentMonthCoupons = Coupon::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $lastMonthCoupons = Coupon::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $couponPercentageChange = $this->calculatePercentageChange($lastMonthCoupons, $currentMonthCoupons);

        $currentMonthRedemptions = Redemption::where('type', 'coupon')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $lastMonthRedemptions = Redemption::where('type', 'coupon')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $redemptionPercentageChange = $this->calculatePercentageChange($lastMonthRedemptions, $currentMonthRedemptions);

        return [
            'totalBusinesses' => Business::count(),
            'totalCoupons' => Coupon::count(),
            'publishedDeals' => Deal::count(),
            'stats' => [
                'businesses' => [
                    'current_month' => $currentMonthBusinesses,
                    'last_month' => $lastMonthBusinesses,
                    'percentage_change' => $businessPercentageChange,
                    'trend' => $businessPercentageChange >= 0 ? 'up' : 'down'
                ],
                'coupons' => [
                    'current_month' => $currentMonthCoupons,
                    'last_month' => $lastMonthCoupons,
                    'percentage_change' => $couponPercentageChange,
                    'trend' => $couponPercentageChange >= 0 ? 'up' : 'down'
                ],
                'coupon_redemptions' => [
                    'current_month' => $currentMonthRedemptions,
                    'last_month' => $lastMonthRedemptions,
                    'percentage_change' => $redemptionPercentageChange,
                    'trend' => $redemptionPercentageChange >= 0 ? 'up' : 'down'
                ]
            ]
        ];
    }

    private function calculatePercentageChange(float $oldValue, float $newValue): float
    {
        if ($oldValue == 0) {
           return $newValue > 0 ? 100.0 : 0.0;
        }

        $change = (($newValue - $oldValue) / $oldValue) * 100;
        return round($change, 2);
    }


    public function getBusinessGrowthStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $twoMonthsAgo = Carbon::now()->subMonths(2)->startOfMonth();

        $currentMonthBusinesses = Business::where('created_at', '>=', $currentMonth)->count();
        $lastMonthBusinesses = Business::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        $twoMonthsAgoBusinesses = Business::whereBetween('created_at', [$twoMonthsAgo, $lastMonth])->count();

        $momGrowth = $this->calculatePercentageChange($twoMonthsAgoBusinesses, $lastMonthBusinesses);

        return [
            'current_month' => $currentMonthBusinesses,
            'last_month' => $lastMonthBusinesses,
            'two_months_ago' => $twoMonthsAgoBusinesses,
            'mom_growth_percentage' => $momGrowth,
            'current_vs_last_month_percentage' => $this->calculatePercentageChange($lastMonthBusinesses, $currentMonthBusinesses)
        ];
    }

    public function getCouponRedemptionStats(): array
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthActiveCoupons = Coupon::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where('created_at', '<=', now())
            ->count();

        $lastMonthActiveCoupons = Coupon::where('is_active', true)
            ->where('valid_from', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->where('valid_until', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('created_at', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->count();

        $currentMonthRedemptions = Redemption::where('type', 'coupon')
            ->whereBetween('created_at', [$currentMonthStart, now()])
            ->count();

        $lastMonthRedemptions = Redemption::where('type', 'coupon')
            ->whereBetween('created_at', [$lastMonthStart, $currentMonthStart])
            ->count();

        $currentRedemptionRate = $currentMonthActiveCoupons > 0
            ? ($currentMonthRedemptions / $currentMonthActiveCoupons) * 100
            : 0;

        $lastRedemptionRate = $lastMonthActiveCoupons > 0
            ? ($lastMonthRedemptions / $lastMonthActiveCoupons) * 100
            : 0;

        $redemptionRateChange = $this->calculatePercentageChange($lastRedemptionRate, $currentRedemptionRate);

        return [
            'redemption_counts' => [
                'current_month' => $currentMonthRedemptions,
                'last_month' => $lastMonthRedemptions,
                'percentage_change' => $this->calculatePercentageChange($lastMonthRedemptions, $currentMonthRedemptions)
            ],
            'redemption_rates' => [
                'current_month' => round($currentRedemptionRate, 2),
                'last_month' => round($lastRedemptionRate, 2),
                'percentage_change' => round($redemptionRateChange, 2)
            ],
            'active_coupons' => [
                'current_month' => $currentMonthActiveCoupons,
                'last_month' => $lastMonthActiveCoupons
            ]
        ];
    }
}
