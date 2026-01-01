<?php

namespace App\Repositories\Admin\Dining;

use App\Models\Dining;

class DiningRepository
{
    public function all(array $filters = [], string $sort = 'created_at', string $order = 'desc', int $perPage = 10)
    {
        $query = Dining::with('category');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%")
                  ->orWhere('location', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['price_range'])) {
            $query->where('price_range', $filters['price_range']);
        }

        if (!empty($filters['featured'])) {
            $query->where('is_featured', filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query
            ->orderBy($sort, $order)
            ->paginate($perPage);
    }

    public function create(array $data): Dining
    {
        return Dining::create($data);
    }

    public function find($id): Dining
    {
        return Dining::with('category')->findOrFail($id);
    }
    public function update($dining, array $data): Dining
    {
        $dining->update($data);
        return $dining;
    }
}
