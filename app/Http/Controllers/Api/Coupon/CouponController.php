<?php

namespace App\Http\Controllers\Api\Coupon;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupons\StoreCouponRequest;
use App\Http\Requests\Coupons\UpdateCouponRequest;
use App\Resources\Coupon\CouponResource;
use App\Services\Coupon\CouponService;
use App\Models\Coupon;
use App\Helpers\ApiResponse;

class CouponController extends Controller
{
    public function __construct(private CouponService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $businessId = request()->query('business_id');
        $businessId = $businessId !== null && $businessId != 0
        ? (int) $businessId
        : auth()->user()->business->id;
        $filters = [
            'search'      => request('search'),
            'category_id' => request('category_id'),
            'status'      => request('status'),   // 1 or 0
            'sort_by'     => request('sort_by'),  // latest, oldest, title_asc, title_desc
        ];
        $coupons = $this->service->list($businessId, $filters);
        return CouponResource::collection($coupons)->additional(['message' => 'Coupons fetched successfully']);
    }

    public function store(StoreCouponRequest $request)
    {
                $businessId = request()->query('business_id');

        $businessId = $businessId !== null && $businessId != 0
        ? (int) $businessId
        : auth()->user()->business->id;

        $coupon = $this->service->create($businessId, $request->validated());

        return ApiResponse::resource(
            new CouponResource($coupon),
            'Coupon created successfully'
        );
    }
    public function show(Coupon $coupon)
    {
        return ApiResponse::resource(new CouponResource($coupon));
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

    public function couponStats()
    {
                $businessId = request()->query('business_id');

        $businessId = $businessId !== null && $businessId != 0
        ? (int) $businessId
        : auth()->user()->business->id;

        $stats = Coupon::where('business_id', $businessId)->selectRaw('is_active, COUNT(*) as total')->groupBy('is_active')->pluck('total', 'is_active');

        $totalCoupons = $stats->sum();

        $response = [
            'stats' => [
                [
                    'status' => "all",
                    'label'  => 'Total',
                    'count'  => $totalCoupons,
                ],
                [
                    'status' => 1,
                    'label'  => 'Active',
                    'count'  => $stats[1] ?? 0,
                ],
                [
                    'status' => 0,
                    'label'  => 'Inactive',
                    'count'  => $stats[0] ?? 0,
                ],
                [
                    'status' => 2,
                    'label'  => 'Expired',
                    'count'  => $stats[2] ?? 0,
                ],
                [
                    'status' => 3,
                    'label'  => 'Draft',
                    'count'  => $stats[3] ?? 0,
                ],
            ],
        ];
        return ApiResponse::success($response, 'Coupon statistics fetched successfully');
    }

}
