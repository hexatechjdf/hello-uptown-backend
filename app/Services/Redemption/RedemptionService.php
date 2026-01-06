<?php

namespace App\Services\Redemption;

use App\Models\Redemption;
use Carbon\Carbon;

class RedemptionService
{
    public function list(array $filters, $businessId)
    {
        $query = Redemption::where('business_id', $businessId)
            ->with([
                'customer:id,name,email,phone',
                'business:id,business_name,logo',
                'coupon:id,title,coupon_code,short_description,image,valid_from,valid_until',
                'deal:id,title,short_description,image,valid_from,valid_until,discount,original_price'
            ]);

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                // Search in coupon title/code for coupon redemptions
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('type', 'coupon')
                        ->whereHas('coupon', function ($couponQuery) use ($search) {
                            $couponQuery->where('title', 'LIKE', "%{$search}%")
                                       ->orWhere('coupon_code', 'LIKE', "%{$search}%");
                        });
                })
                // Search in deal title for deal redemptions
                ->orWhere(function ($subQ) use ($search) {
                    $subQ->where('type', 'deal')
                        ->whereHas('deal', function ($dealQuery) use ($search) {
                            $dealQuery->where('title', 'LIKE', "%{$search}%");
                        });
                })
                // Search in customer details
                ->orWhereHas('customer', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Type filter (coupon or deal)
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Parent ID filter (coupon_id or deal_id)
        if (!empty($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        // Coupon-specific filter
        if (!empty($filters['coupon_id'])) {
            $query->where('type', 'coupon')
                  ->where('parent_id', $filters['coupon_id']);
        }

        // Deal-specific filter
        if (!empty($filters['deal_id'])) {
            $query->where('type', 'deal')
                  ->where('parent_id', $filters['deal_id']);
        }

        // Customer filter
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Category filter (works for both coupons and deals)
        if (!empty($filters['category_id'])) {
            $query->where(function ($q) use ($filters) {
                $q->where(function ($subQ) use ($filters) {
                    $subQ->where('type', 'coupon')
                        ->whereHas('coupon', function ($couponQuery) use ($filters) {
                            $couponQuery->where('category_id', $filters['category_id']);
                        });
                })->orWhere(function ($subQ) use ($filters) {
                    $subQ->where('type', 'deal')
                        ->whereHas('deal', function ($dealQuery) use ($filters) {
                            $dealQuery->where('category_id', $filters['category_id']);
                        });
                });
            });
        }

        // Time period filter
        if (!empty($filters['time']) && $filters['time'] !== 'all') {
            match ($filters['time']) {
                'today' => $query->whereDate('created_at', Carbon::today()),
                'this_week' => $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]),
                'this_month' => $query->whereMonth('created_at', Carbon::now()->month)
                                      ->whereYear('created_at', Carbon::now()->year),
                'last_7_days' => $query->where('created_at', '>=', Carbon::now()->subDays(7)),
                'last_30_days' => $query->where('created_at', '>=', Carbon::now()->subDays(30)),
                default => null
            };
        }

        // Date range filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay()
            ]);
        } elseif (!empty($filters['start_date'])) {
            $query->where('created_at', '>=', Carbon::parse($filters['start_date'])->startOfDay());
        } elseif (!empty($filters['end_date'])) {
            $query->where('created_at', '<=', Carbon::parse($filters['end_date'])->endOfDay());
        }

        // Featured filter (for deals) - only applies to deal redemptions
        if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
            $query->where('type', 'deal')
                  ->whereHas('deal', function ($subQuery) use ($filters) {
                      $subQuery->where('is_featured', $filters['is_featured']);
                  });
        }

        // Active filter (for coupons) - only applies to coupon redemptions
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('type', 'coupon')
                  ->whereHas('coupon', function ($subQuery) use ($filters) {
                      $subQuery->where('is_active', $filters['is_active']);
                  });
        }

        // Sorting
        $sortOrder = ($filters['sort'] ?? 'newest') === 'oldest' ? 'asc' : 'desc';
        $query->orderBy('redeemed_at', $sortOrder);

        return $query->paginate($filters['per_page'] ?? 10);
    }

}
