<?php
namespace App\Repositories;

use App\Models\Porchfest;

class PorchfestRepository
{
    public function list(array $filters)
    {
        return Porchfest::query()
            ->when($filters['search'] ?? null, fn ($q, $s) =>
                $q->where('heading', 'like', "%$s%")
            )
            ->when($filters['status'] ?? null, fn ($q, $s) =>
                $q->where('status', $s)
            )
            ->orderBy(
                $filters['sort_by'] ?? 'event_date',
                $filters['sort_order'] ?? 'desc'
            )
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

?>