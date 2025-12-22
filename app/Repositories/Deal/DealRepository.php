<?php

namespace App\Repositories\Deal;

use App\Models\Deal;

class DealRepository
{

       protected $model;

    public function __construct(Deal $deal)
    {
        $this->model = $deal;
    }

   public function search(array $filters, int $businessId)
    {
        return Deal::where('business_id', $businessId)
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('title', 'LIKE', "%{$search}%");
            })
            ->when(isset($filters['featured']), function ($q) {
                $q->where('is_featured', 1);
            })
            ->when($filters['category_id'] ?? null, function ($q, $categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when(isset($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when($filters['sort_by'] ?? null, function ($q) use ($filters) {
                match ($filters['sort_by']) {
                    'created_date' => $q->orderBy('created_at', 'desc'),
                    'discount'     => $q->orderBy('discount', 'desc'),
                    'expiry'       => $q->orderBy('valid_until', 'asc'),
                    default        => $q->latest(),
                };
            }, fn ($q) => $q->latest())
            ->paginate($filters['per_page'] ?? 10);
    }

    public function create(array $data)
    {
        return Deal::create($data);
    }

    public function update(Deal $deal, array $data)
    {
        $deal->update($data);
        return $deal;
    }

    public function delete(Deal $deal)
    {
        return $deal->delete();
    }
    public function all($filters = [], $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->query()->where('status', true);

        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%")
                ->orWhere('short_description', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
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
                    // $query->orderByDesc('sold_count'); // assuming sold_count field exists
                    break;
                case 'newest':
                    $query->orderByDesc('created_at');
                    break;
                case 'expiringSoon':
                    $query->orderBy('valid_until', 'asc');
                    break;
            }
        } else {
            $query->orderBy($sort, $order);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get popular deals (top sold)
     */
    public function getPopularDeals($limit = 5)
    {
        return $this->model->where('status', true)
            // ->orderByDesc('sold_count') // or 'views_count' if sold not present
            ->take($limit)
            ->get();
    }

    /**
     * Get expiring soon deals
     */
    public function getExpiringSoonDeals($limit = 5)
    {
        return $this->model->where('status', true)
            ->whereNotNull('valid_until')
            ->orderBy('valid_until', 'asc')
            ->take($limit)
            ->get();
    }
    public function getDealOfTheWeek()
    {
        // Assuming we have a boolean 'deal_of_week' column
        return $this->model->where('status', true)
            // ->where('deal_of_week', true)
            ->first();
    }

    /**
     * Get other great deals excluding a specific deal
     */
    public function getOtherGreatDeals($excludeId, $limit = 4)
    {
        return $this->model->where('status', true)
            ->where('id', '<>', $excludeId)
            // ->orderByDesc('sold_count') // or 'rating' if available
            ->take($limit)
            ->get();
    }
}
