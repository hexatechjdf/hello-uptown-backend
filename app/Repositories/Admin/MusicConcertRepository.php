<?php

namespace App\Repositories\Admin;

use App\Models\MusicConcert;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MusicConcertRepository
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = MusicConcert::with('category');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('main_heading', 'like', "%{$filters['search']}%")
                  ->orWhere('artist', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['featured'])) {
            $query->where('featured', $filters['featured']);
        }

        $query->orderBy(
            $filters['sortBy'] ?? 'event_date',
            $filters['order'] ?? 'desc'
        );

        return $query->paginate($filters['perPage'] ?? 10);
    }

    public function find(int $id): ?MusicConcert
    {
        return MusicConcert::with('category')->find($id);
    }

    public function create(array $data): MusicConcert
    {
        return MusicConcert::create($data);
    }

    public function update(MusicConcert $concert, array $data): MusicConcert
    {
        $concert->update($data);
        return $concert->fresh(['category']);
    }

    public function delete(MusicConcert $concert): bool
    {
        return (bool) $concert->delete();
    }
}
