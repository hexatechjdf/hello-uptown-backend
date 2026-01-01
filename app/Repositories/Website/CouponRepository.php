<?php

namespace App\Repositories\Website;

use App\Models\Coupon;
use App\Scopes\BusinessScope;

class CouponRepository
{
    protected $model;

    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    public function all($filters = [], $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('short_description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['discount_type'])) {
            $query->where('discount_type', $filters['discount_type']);
        }

        if (!empty($filters['filter'])) {
            switch ($filters['filter']) {
                case 'newest':
                    $query->orderByDesc('created_at');
                    break;
                case 'expiringSoon':
                    $query->whereNotNull('valid_until')->orderBy('valid_until', 'asc');
                    break;
                case 'mostPopular':
                    $query->orderByDesc('used_count');
                    break;
            }
        } else {
            $query->orderBy($sort, $order);
        }

        return $query->paginate($perPage);
    }

    /**
     * Count available coupons
     */
    public function countAvailableCoupons()
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->whereRaw('valid_until >= NOW()')
            ->count();
    }

    /**
     * Count expiring soon coupons
     */
    public function countExpiringSoonCoupons()
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->whereBetween('valid_until', [now(), now()->addDays(7)])
            ->count();
    }

    /**
     * Count new coupons (created in last 7 days)
     */
    public function countNewCoupons()
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->count();
    }

    /**
     * Get single coupon by ID
     */
    public function findActive($id)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get coupons by category
     */
    public function getByCategory($category, $limit = 10)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('category', $category)
            ->where('is_active', true)
            ->limit($limit)
            ->get();
    }

    /**
     * Get expiring soon coupons
     */
    public function getExpiringSoon($limit = 10)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('is_active', true)
            ->whereBetween('valid_until', [now(), now()->addDays(7)])
            ->orderBy('valid_until', 'asc')
            ->limit($limit)
            ->get();
    }
}
