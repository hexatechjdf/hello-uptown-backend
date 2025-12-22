<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Helpers\ApiResponse;
use App\Resources\Coupon\CouponResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }


    public function metrics(Request $request)
    {
        $businessId = Auth::user()->business->id;
        $duration   = $request->input('duration'); // 1, 2, null
        $data = $this->dashboard->getMetrics($businessId, $duration);
        return ApiResponse::success($data, 'Dashboard metrics loaded');
    }

    public function topCoupons(Request $request)
    {
        $businessId = Auth::user()->business->id;
        $duration   = $request->input('duration');
        $coupons = $this->dashboard->topCoupons($businessId, $duration);
        return ApiResponse::collection(
            CouponResource::collection($coupons),
            'Top active coupons loaded'
        );
    }

    public function recentRedemptions(Request $request)
    {
        $businessId = Auth::user()->business->id;
        $duration   = $request->input('duration');
        $redemptions = $this->dashboard->recentRedemptions($businessId, $duration);
        return ApiResponse::success($redemptions, 'Recent redemptions loaded');
    }
}
