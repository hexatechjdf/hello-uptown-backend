<?php
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

        return $query
            ->orderBy('event_date', $filters['sort'] ?? 'desc')
            ->paginate(10);
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
?>