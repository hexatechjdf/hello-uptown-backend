<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\Website\CouponService;
use App\Resources\Coupon\CouponResource;

class RedemptionController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function redemption(Request $request)
    {
        $data = $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'required|string|max:20',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $result = $this->couponService->redeemCoupon($data);
        
        if (!$result) {
            return ApiResponse::error('Coupon cannot be redeemed or usage limit reached', null, 403);
        }

        if (isset($result['error']) && $result['error'] === 'outside_radius') {
            return ApiResponse::error(
                'You are outside the redemption range',
                [
                    'distance_in_meters' => $result['distance_in_meters'],
                    'allowed_radius' => $result['allowed_radius']
                ],
                403
            );
        }

        // Wrap redeemed coupon with CouponResource
        $couponResource = new CouponResource($result['coupon']);

        return ApiResponse::success([
            'redemption_id' => $result['redemption_id'],
            'coupon' => $couponResource,
            'distance_in_meters' => $result['distance_in_meters']
        ], 'Coupon redeemed successfully');
    }
}
