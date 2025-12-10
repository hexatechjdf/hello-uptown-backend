<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Coupon\CouponRepository;
use App\Services\Coupon\CouponService;
use App\Http\Resources\CouponResource;
use App\Helpers\ApiResponse;

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

    /**
     * Show coupon details
     */
    public function show($id)
    {
        $coupon = $this->repo->find($id);
        return ApiResponse::resource(new CouponResource($coupon));
    }
}
