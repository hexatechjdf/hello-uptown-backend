<?php
class FarmerMarketService
{
    public function store(array $data)
    {
        if (!empty($data['image'])) {
            $data['image'] = $data['image']->store('farmer-markets', 'public');
        }

        return app(FarmerMarketRepository::class)->create($data);
    }

    public function update(FarmerMarket $market, array $data)
    {
        if (!empty($data['image'])) {
            $data['image'] = $data['image']->store('farmer-markets', 'public');
        }

        return app(FarmerMarketRepository::class)->update($market, $data);
    }
}
