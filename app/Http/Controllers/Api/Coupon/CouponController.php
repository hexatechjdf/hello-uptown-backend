<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupons\StoreCouponRequest;
use App\Http\Requests\Coupons\UpdateCouponRequest;
use App\Http\Resources\Coupon\CouponResource;
use App\Services\CouponService;
use App\Models\Coupon;
use App\Helpers\ApiResponse;

class CouponController extends Controller
{
    public function __construct(private CouponService $service) {
        $this->service = $service;
    }

    public function index()
    {
        $businessId = auth()->user()->business->id;
        $search = request('search');

        $coupons = $this->service->list($businessId, $search);

        return CouponResource::collection($coupons)
            ->additional(['message' => 'Coupons fetched successfully']);
    }

    public function store(StoreCouponRequest $request)
    {
        $businessId = auth()->user()->business->id;

        $coupon = $this->service->create($businessId, $request->validated());

        return ApiResponse::resource(
            new CouponResource($coupon),
            'Coupon created successfully'
        );
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $coupon = $this->service->update($coupon, $request->validated());

        return ApiResponse::resource(
            new CouponResource($coupon),
            'Coupon updated successfully'
        );
    }

    public function destroy(Coupon $coupon)
    {
        $this->service->delete($coupon);

        return ApiResponse::success([], 'Coupon deleted successfully');
    }
}
