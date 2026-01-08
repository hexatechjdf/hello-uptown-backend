<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\Website\CouponService;
use App\Resources\Coupon\CouponResource;
use App\Models\Customer;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Redemption;
use App\Models\NewsletterSubscription;
use App\Resources\Website\CustomerResource;
use App\Scopes\BusinessScope;
use Carbon\Carbon;

class RedemptionController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
 private function getItemDetails($type, $parentId)
    {
        if ($type === 'coupon') {
            $item = Coupon::withoutGlobalScope(BusinessScope::class)
                ->with('business:id,latitude,longitude,redemption_radius,business_name')
                ->find($parentId);
            $itemName = 'coupon';
        } else {
            $item = Deal::withoutGlobalScope(BusinessScope::class)
                ->with('business:id,latitude,longitude,redemption_radius,name')
                ->find($parentId);
            $itemName = 'deal';
        }

        return ['item' => $item, 'itemName' => $itemName];
    }

    public function validateOrCreateCustomer(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email',
            'parent_id'         => 'required|integer',
            'type'              => 'required|in:deal,coupon',
            'phone'             => 'required|string|max:20',
            'terms_condition'   => 'required|boolean',
            'send_new_deals'    => 'required|boolean',
        ]);

        $customer = Customer::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'             => $data['name'],
                'phone'            => $data['phone'],
                'terms_condition'  => $data['terms_condition'],
                'send_new_deals'   => $data['send_new_deals'],
            ]
        );

        if (!$customer->wasRecentlyCreated) {
            $customer->update([
                'name'             => $data['name'],
                'phone'            => $data['phone'],
                'terms_condition'  => $data['terms_condition'],
                'send_new_deals'   => $data['send_new_deals'],
            ]);
        }

        if ($data['send_new_deals']) {
            NewsletterSubscription::firstOrCreate(
                ['email' => $data['email']],
                [
                    'status' => 'active',
                    'subscribed_at' => now(),
                ]
            );
        }

        $itemDetails = $this->getItemDetails($data['type'], $data['parent_id']);
        $coupon = $itemDetails['item'];

        // Check if item exists
        if (!$coupon) {
            return ApiResponse::error(
                ucfirst($itemDetails['itemName']) . ' not found',
                null,
                404
            );
        }

        $itemResource = $data['type'] === 'coupon'
            ? new CouponResource($coupon)
            : $coupon;

        return ApiResponse::success([
            'customer' => new CustomerResource($customer),
            'item' => $itemResource,
            'is_new_customer' => $customer->wasRecentlyCreated,
        ], $customer->wasRecentlyCreated ? 'Customer created successfully' : 'Customer updated successfully');
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

        $result = $this->couponService->redeemCoupon($data);

        if (isset($result['error'])) {
            $errorData = $result;
            $message = $result['message'] ?? 'Unable to redeem';
            unset($errorData['message']);
            return ApiResponse::error($message, $errorData, 403);
        }

        if (!isset($result['success']) || !$result['success']) {
            return ApiResponse::success($result, $result['message'] ?? 'Operation completed');
        }

        return ApiResponse::success([
            'redemption_id' => $result['redemption_id'],
            'coupon' => new CouponResource($result['coupon']),
            'distance_in_meters' => $result['distance_in_meters'],
            'discount_amount' => $result['discount_amount'] ?? 0,
            'redemption_count' => $result['redemption_count'] ?? 0,
            'redemption_dates' => $result['redemption_dates'] ?? [],
        ], $result['message'] ?? 'Coupon redeemed successfully');
    }

    public function checkEligibility(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type'        => 'required|in:deal,coupon',
            'parent_id'   => 'required|integer',
        ]);

        $result = $this->couponService->checkEligibility($data);

        return ApiResponse::success($result, 'Eligibility check completed');
    }
}
