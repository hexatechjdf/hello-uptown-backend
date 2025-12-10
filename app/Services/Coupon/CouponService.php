<?php

namespace App\Services\Coupon;

use App\Models\Coupon;

class CouponService
{
    public function list($businessId, $search)
    {
        return Coupon::where('business_id', $businessId)
            ->when($search, fn($q) =>
                $q->where('title', 'like', "%$search%")
                  ->orWhere('coupon_code', 'like', "%$search%")
            )
            ->latest()
            ->paginate(12);
    }

    public function create($businessId, array $data): Coupon
    {
        $data['business_id'] = $businessId;
        return Coupon::create($data);
    }

    public function update(Coupon $coupon, array $data): Coupon
    {
        $coupon->update($data);
        return $coupon;
    }

    public function delete(Coupon $coupon): bool
    {
        return $coupon->delete();
    }
}
