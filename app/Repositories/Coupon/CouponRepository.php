<?php

namespace App\Repositories\Coupon;

use App\Models\Coupon;

class CouponRepository
{

     protected $model;

    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    public function search(array $filters, int $businessId)
    {
        return Coupon::where('business_id', $businessId)
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('title', 'LIKE', "%{$search}%");
            })
            ->when($filters['featured'] ?? null, fn($q) => $q->where('is_featured', true))
            ->when($filters['category_id'] ?? null, fn($q, $category) => $q->where('category_id', $category))
            ->orderBy($filters['sort_by'] ?? 'id', $filters['sort_order'] ?? 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    public function create(array $data)
    {
        return Coupon::create($data);
    }

    public function update(Coupon $deal, array $data)
    {
        $deal->update($data);
        return $deal;
    }

    public function delete(Coupon $deal)
    {
        return $deal->delete();
    }
    public function all($filters = [], $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->query()
        ->where('is_active', true)
        ;

        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%")
                ->orWhere('short_description', 'like', "%{$filters['search']}%");
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
                    $query->orderByDesc('used_count'); // or another popularity metric
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
        return $this->model
        ->where('is_active', true)
            ->whereRaw('valid_until >= NOW()')
            ->count();
    }

    /**
     * Count expiring soon coupons
     */
    public function countExpiringSoonCoupons()
    {
        return $this->model
        ->where('is_active', true)
            ->whereBetween('valid_until', [now(), now()->addDays(7)])
            ->count();
    }

    /**
     * Count new coupons (created in last 7 days)
     */
    public function countNewCoupons()
    {
        return $this->model
        ->where('is_active', true)
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->count();
    }
}
