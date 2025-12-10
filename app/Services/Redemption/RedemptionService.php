<?php
class RedemptionService
{
    public function list(array $filters)
    {
        $query = Redemption::with(['coupon:id,title,coupon_code', 'user:id,first_name,last_name,email']);

        // 🔍 Search (coupon title or coupon code)
        if (!empty($filters['search'])) {
            $query->whereHas('coupon', function ($q) use ($filters) {
                $q->where('title', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('coupon_code', 'LIKE', "%{$filters['search']}%");
            });
        }

        // 🎯 Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 🎟 Filter by coupon
        if (!empty($filters['coupon_id'])) {
            $query->where('coupon_id', $filters['coupon_id']);
        }

        // 🔽 Sorting
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($filters['per_page'] ?? 20);
    }
}
