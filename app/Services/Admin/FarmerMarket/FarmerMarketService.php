<?php

namespace App\Services\Admin\FarmerMarket;

use App\Models\FarmerMarket;
use App\Repositories\Admin\FarmerMarket\FarmerMarketRepository;

class FarmerMarketService
{
    protected FarmerMarketRepository $repo;
    public function __construct(FarmerMarketRepository $repo)
    {
        $this->repo = $repo;
    }
    public function store(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(FarmerMarket $market, array $data)
    {
        return $this->repo->update($market, $data);
    }

    public function delete(FarmerMarket $market)
    {
        return $this->repo->delete($market);
    }

    public function paginate(array $filters)
    {
        return $this->repo->paginate($filters);
    }
}
