<?php
namespace App\Repositories\Admin\Porchfest;

use App\Models\Porchfest;

class PorchfestRepository
{
    public function list(array $filters)
    {
        $query = Porchfest::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('artist', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['featured'])) {
            $query->where('is_featured', filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN));
        }

        $sort = $filters['sort_order'] ?? 'desc';
        $sortBy = $filters['sort_by'] ?? 'created_at';

        return $query
            ->orderBy($sortBy, $sort)
            ->paginate($filters['per_page'] ?? 10);
    }

    public function create(array $data): Porchfest
    {
        return Porchfest::create($data);
    }

    public function update(Porchfest $porchfest, array $data): Porchfest
    {
        $porchfest->update($data);
        return $porchfest;
    }

    public function delete(Porchfest $porchfest): void
    {
        $porchfest->delete();
    }
}
