<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Coupon\CouponService;
use App\Helpers\ApiResponse;
use App\Models\Coupon;
use App\Repositories\Coupon\CouponRepository;
use App\Resources\Coupon\CouponResource;

class CouponController extends Controller
{
    protected $service;
    protected $repo;

    public function __construct(CouponService $service, CouponRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }

    /**
     * List all coupons with filters, search, sorting, pagination
     * Also return 3 card counts
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'discount_type' => $request->input('discount_type'),
            'filter' => $request->input('filter'), // newest, expiringSoon, mostPopular
        ];

        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $perPage = $request->input('perPage', 10);

        $coupons = $this->repo->all($filters, $sort, $order, $perPage);

        // 3 card counts
        $availableCount = $this->repo->countAvailableCoupons();
        $expiringSoonCount = $this->repo->countExpiringSoonCoupons();
        $newCouponsCount = $this->repo->countNewCoupons();

        return ApiResponse::success([
            'coupons' => CouponResource::collection($coupons),
            'cards' => [
                'availableCoupons' => $availableCount,
                'expiringSoonCoupons' => $expiringSoonCount,
                'newCoupons' => $newCouponsCount,
            ],
        ], 'Coupons retrieved successfully');
    }

    public function show($id)
    {
        $Coupon = Coupon::where('id', $id)->where('is_active', true)->first();
        if (!$Coupon) {
            return ApiResponse::error('Coupon not found', 404);
        }
        return ApiResponse::collection(CouponResource::collection(collect([$Coupon])), 'Coupon fetched successfully');
    }
}
