<?php

namespace App\Repositories\Admin\FarmerMarket;

use App\Models\FarmerMarket;

class FarmerMarketRepository
{
    public function paginate(array $filters)
    {
        return FarmerMarket::query()
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($filters['featured'] ?? null, fn ($q, $f) => $q->where('featured', $f))
            ->when($filters['search'] ?? null, function ($q, $s) {
                $q->where('heading', 'like', "%{$s}%")
                  ->orWhere('subheading', 'like', "%{$s}%");
            })
            ->orderBy(
                $filters['sort_by'] ?? 'date',
                $filters['sort_dir'] ?? 'desc'
            )
            ->paginate($filters['per_page'] ?? 10);
    }

    public function create(array $data)
    {
        return FarmerMarket::create($data);
    }

    public function update(FarmerMarket $market, array $data)
    {
        $market->update($data);
        return $market;
    }

    public function delete(FarmerMarket $market)
    {
        return $market->delete();
    }
}
?>