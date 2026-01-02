<?php

namespace App\Repositories\Admin;

use App\Models\MusicConcert;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MusicConcertRepository
{
    public function all($filters = [], $sort = 'event_date', $order = 'desc', $perPage = 10): LengthAwarePaginator
    {
        $query = MusicConcert::with('category');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('main_heading', 'like', "%{$filters['search']}%")
                  ->orWhere('artist', 'like', "%{$filters['search']}%")
                  ->orWhere('event_description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['featured'])) {
            $query->where('featured', filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN));
        }

        // Use the parameters passed from controller instead of filters array
        return $query->orderBy($sort, $order)->paginate($perPage);
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
