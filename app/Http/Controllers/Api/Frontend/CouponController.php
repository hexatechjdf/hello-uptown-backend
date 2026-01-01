<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Repositories\Website\CouponRepository;
use App\Resources\Coupon\CouponResource;

class CouponController extends Controller
{
    protected $repo;

    public function __construct(CouponRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * List all coupons with filters, search, sorting, pagination
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
        $coupon = $this->repo->findActive($id);

        if (!$coupon) {
            return ApiResponse::error('Coupon not found', 404);
        }

        return ApiResponse::success(
            new CouponResource($coupon),
            'Coupon fetched successfully'
        );
    }

    /**
     * New method: Get featured coupons
     */
    public function featured(Request $request)
    {
        $limit = $request->input('limit', 8);
        $coupons = $this->repo->getFeaturedCoupons($limit);

        return ApiResponse::success(
            CouponResource::collection($coupons),
            'Featured coupons retrieved successfully'
        );
    }

    /**
     * New method: Get expiring soon coupons
     */
    public function expiringSoon(Request $request)
    {
        $limit = $request->input('limit', 10);
        $coupons = $this->repo->getExpiringSoon($limit);

        return ApiResponse::success(
            CouponResource::collection($coupons),
            'Expiring soon coupons retrieved successfully'
        );
    }
}
