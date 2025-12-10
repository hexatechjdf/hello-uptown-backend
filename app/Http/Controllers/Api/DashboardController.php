<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Helpers\ApiResponse;
use App\Resources\Coupon\CouponResource;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function metrics()
    {
        $businessId = Auth::user()->business->id;

        $data = $this->dashboard->getMetrics($businessId);

        return ApiResponse::success($data, 'Dashboard metrics loaded');
    }

    public function topCoupons()
    {
        $businessId = Auth::user()->business->id;

        $coupons = $this->dashboard->topCoupons($businessId);
        return ApiResponse::collection(
            CouponResource::collection($coupons),
            'Top active coupons loaded'
        );
    }

    public function recentRedemptions()
    {
        $businessId = Auth::user()->business->id;

        $redemptions = $this->dashboard->recentRedemptions($businessId);

        return ApiResponse::success($redemptions, 'Recent redemptions loaded');
    }
}
