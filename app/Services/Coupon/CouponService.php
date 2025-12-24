<?php

namespace App\Services\Coupon;

use App\Models\Coupon;

class CouponService
{
  public function list($businessId, array $filters = [])
    {
        return Coupon::where('business_id', $businessId)->with('business')
            ->withCount('redemptions')
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('coupon_code', 'like', "%{$search}%");
                });
            })
            ->when($filters['category_id'] ?? null, function ($q, $categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when(isset($filters['status']), function ($q) use ($filters) {
                $q->where('is_active', $filters['status']);
            })
            ->when($filters['sort_by'] ?? null, function ($q, $sortBy) {
                match ($sortBy) {
                    'created_latest'   => $q->orderBy('created_at', 'desc'),
                    'created_oldest'   => $q->orderBy('created_at', 'asc'),
                    'expiry_soon'      => $q->orderBy('valid_until', 'asc'),
                    'expiry_late'      => $q->orderBy('valid_until', 'desc'),
                    'most_redeemed'    => $q->orderBy('redemptions_count', 'desc'),
                    'least_redeemed'   => $q->orderBy('redemptions_count', 'asc'),
                    default            => $q->latest(),
                };
            }, fn ($q) => $q->latest())

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
