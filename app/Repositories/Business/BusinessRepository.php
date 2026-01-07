<?php

namespace App\Repositories\Business;

use App\Models\Business;

class BusinessRepository
{
    protected $model;

    public function __construct(Business $business)
    {
        $this->model = $business;
    }

    public function update(Business $business, array $data)
    {
        return $business->update($data);
    }

    public function findByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }
    public function all($filters = [], $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->query()->with(['categorydata', 'category']);
        if (!empty($filters['search'])) {
            $query->where('business_name', 'like', "%{$filters['search']}%")
                ->orWhere('short_description', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', (int)$filters['category_id']); // assuming category field exists
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status'] === 'active');
        }

        if (!empty($filters['filter'])) {
            switch ($filters['filter']) {
                case 'featured':
                    // $query->where('featured', true);
                    break;
                case 'mostPopular':
                    // $query->orderByDesc('rating'); // assuming rating field
                    break;
                case 'newest':
                    $query->orderByDesc('created_at');
                    break;
            }
        } else {
            $query->orderBy($sort, $order);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get popular businesses
     */
    public function getPopularBusinesses($limit = 5)
    {
        return $this->model->where('status', true)
            // ->orderByDesc('rating')
            ->take($limit)
            ->get();
    }

    /**
     * Get featured businesses
     */
    public function getFeaturedBusinesses($limit = 5)
    {
        return $this->model->where('status', true)
            // ->where('featured', true)
            ->take($limit)
            ->get();
    }
}
