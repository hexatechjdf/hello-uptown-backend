<?php

namespace App\Services\Redemption;

use App\Models\Redemption;
use Carbon\Carbon;

class RedemptionService
{
    public function list(array $filters, $businessId)
    {
        $query = Redemption::where('business_id', $businessId)->with([
            'coupon:id,title,coupon_code',
            'user:id,first_name,last_name,email'
        ]);
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('coupon', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('coupon_code', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['coupon_id'])) {
            $query->where('coupon_id', $filters['coupon_id']);
        }
        if (!empty($filters['time']) && $filters['time'] !== 'all') {
            match ($filters['time']) {
                'today' => $query->whereDate('created_at', Carbon::today()),
                'this_week' => $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]),
                'this_month' => $query->whereMonth('created_at', Carbon::now()->month)
                                      ->whereYear('created_at', Carbon::now()->year),
                default => null
            };
        }
        $query->orderBy('redeemed_at',($filters['sort'] ?? 'newest') === 'oldest' ? 'asc' : 'desc');

        return $query->paginate($filters['per_page'] ?? 10);
    }
}
