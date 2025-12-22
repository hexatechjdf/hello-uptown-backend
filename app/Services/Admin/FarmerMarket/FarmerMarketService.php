<?php
namespace App\Services\Admin\FarmerMarket;

use App\Models\FarmerMarket;
use App\Repositories\Admin\FarmerMarket\FarmerMarketRepository;

class FarmerMarketService
{
    public function store(array $data)
    {
        return app(FarmerMarketRepository::class)->create($data);
    }

    public function update(FarmerMarket $market, array $data)
    {
        return app(FarmerMarketRepository::class)->update($market, $data);
    }
}
