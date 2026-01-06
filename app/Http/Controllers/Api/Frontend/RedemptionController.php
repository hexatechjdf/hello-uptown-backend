<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\Website\CouponService;
use App\Resources\Coupon\CouponResource;
use App\Models\Customer;
use App\Resources\Customer\CustomerResource;

class RedemptionController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * First API call: Validate/Create customer
     */
    public function validateOrCreateCustomer(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'required|string|max:20',
        ]);

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'  => $data['name'],
                'phone' => $data['phone'],
            ]
        );

        // Update customer info if existing but details changed
        if (!$customer->wasRecentlyCreated) {
            $customer->update([
                'name'  => $data['name'],
                'phone' => $data['phone'],
            ]);
        }

        return ApiResponse::success([
            'customer' => new CustomerResource($customer),
        ], 'Customer validated successfully');
    }


    public function redemption(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type'        => 'required|in:deal,coupon',
            'parent_id'   => 'required|integer',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        $customer = Customer::find($data['customer_id']);

        if (!$customer) {
            return ApiResponse::error('Customer not found', null, 404);
        }

        $redemptionData = [
            'customer_id' => $customer->id,
            'type'        => $data['type'],
            'parent_id'   => $data['parent_id'],
            'latitude'    => $data['latitude'],
            'longitude'   => $data['longitude'],
        ];

        $result = $this->couponService->redeemCoupon($redemptionData);

        if (isset($result['error'])) {
            $errorData = $result;
            $message = $result['message'] ?? 'Unable to redeem';
            unset($errorData['message']);

            return ApiResponse::error($message, $errorData, 403);
        }

        if (!isset($result['success']) || !$result['success']) {
            return ApiResponse::error($result['message'], null, 403);
        }

        $couponResource = new CouponResource($result['coupon']);

        return ApiResponse::success([
            'redemption_id' => $result['redemption_id'],
            'coupon' => $couponResource,
            'distance_in_meters' => $result['distance_in_meters'],
            'discount_amount' => $result['discount_amount'] ?? 0
        ], $result['message'] ?? 'Coupon redeemed successfully');
    }
}
