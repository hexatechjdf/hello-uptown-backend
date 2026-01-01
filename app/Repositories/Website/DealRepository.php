<?php

namespace App\Repositories\Website;

use App\Models\Deal;
use App\Scopes\BusinessScope;

class DealRepository
{
    protected $model;

    public function __construct(Deal $deal)
    {
        $this->model = $deal;
    }

    /**
     * Get all deals for frontend without BusinessScope
     */
    public function all($filters = [], $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('short_description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['business_id'])) {
            $query->where('business_id', $filters['business_id']);
        }

        if (!empty($filters['price_min'])) {
            $query->where('discounted_price', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('discounted_price', '<=', $filters['price_max']);
        }

        if (!empty($filters['filter'])) {
            switch ($filters['filter']) {
                case 'mostPopular':
                    // Add popularity logic when available
                    // $query->orderByDesc('sold_count');
                    break;
                case 'newest':
                    $query->whereBetween('created_at', [
                        now()->subWeek(),
                        now()
                    ])->orderBy('created_at', 'desc');
                    break;
                case 'expiringSoon':
                    $query->whereNotNull('valid_until')
                        ->whereBetween('valid_until', [
                            now(),
                            now()->addWeek()
                        ])->orderBy('valid_until', 'asc');
                    break;
                default:
                    $query->orderBy($sort, $order);
                    break;
            }
        } else {
            $query->orderBy($sort, $order);
        }

        // Return all or paginated
        if ((int) $perPage === 0) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    /**
     * Get popular deals without BusinessScope
     */
    public function getPopularDeals($limit = 5)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            // Uncomment when you have the column
            // ->orderByDesc('sold_count')
            // ->take($limit)
            ->get();
    }

    /**
     * Get newest deals without BusinessScope
     */
    public function getNewestDeals($limit = 5)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            ->whereBetween('created_at', [
                now()->subWeek(),
                now()
            ])
            ->orderBy('created_at', 'desc')
            // ->take($limit)
            ->get();
    }

    /**
     * Get expiring soon deals without BusinessScope
     */
    public function getExpiringSoonDeals($limit = 5)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            ->whereNotNull('valid_until')
            ->whereBetween('valid_until', [
                now(),
                now()->addWeek()
            ])
            ->orderBy('valid_until', 'asc')
            // ->take($limit)
            ->get();
    }

    /**
     * Get deal of the week without BusinessScope
     */
    public function getDealOfTheWeek()
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            // Uncomment when you have the column
            // ->where('deal_of_week', true)
            ->first();
    }

    /**
     * Get other great deals excluding a specific deal without BusinessScope
     */
    public function getOtherGreatDeals($excludeId, $limit = 4)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            ->where('id', '<>', $excludeId)
            // Uncomment when you have popularity logic
            // ->orderByDesc('sold_count')
            ->take($limit)
            ->get();
    }

    /**
     * Find active deal by ID without BusinessScope
     */
    public function findActive($id)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('id', $id)
            ->where('status', true)
            ->first();
    }

    /**
     * Get top deals of the month without BusinessScope
     */
    public function topDealsOfMonth($limit = 5)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            ->whereMonth('created_at', now()->month)
            ->take($limit)
            ->get();
    }

    /**
     * Get deals by category without BusinessScope
     */
    public function getByCategory($categoryId, $limit = 10)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            ->where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get featured deals without BusinessScope
     */
    public function getFeaturedDeals($limit = 8)
    {
        return $this->model->withoutGlobalScope(BusinessScope::class)
            ->where('status', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}
