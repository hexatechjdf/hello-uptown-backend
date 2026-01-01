<?php
namespace App\Repositories\Admin\ArtFair;
use App\Models\ArtFair;

class ArtFairRepository
{
    public function list(array $filters)
    {
        $query = ArtFair::query();

        if (!empty($filters['search'])) {
            $query->where('heading', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['featured'])) {
            $query->where('featured', filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN));
        }

        $sort = $filters['sort'] ?? 'desc';
        $sortBy = $filters['sort_by'] ?? 'event_date';

        return $query
            ->orderBy($sortBy, $sort)
            ->paginate($filters['per_page'] ?? 10);
    }

    public function create(array $data)
    {
        return ArtFair::create($data);
    }

    public function update(ArtFair $artFair, array $data)
    {
        $artFair->update($data);
        return $artFair;
    }

    public function delete(ArtFair $artFair)
    {
        $artFair->delete();
    }
}
