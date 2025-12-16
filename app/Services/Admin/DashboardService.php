<?php

namespace App\Services\Admin;

use App\Models\Business;
use App\Models\Coupon;
use App\Models\Deal;

class DashboardService
{
    public function stats(): array
    {
        return [
            'totalBusinesses' => Business::count(),
            'totalCoupons'    => Coupon::count(),
            'publishedDeals'  => Deal::count(),
        ];
    }
}
