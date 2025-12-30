<?php

namespace App\Services\Admin\Advertisement;

use App\Repositories\Admin\Advertisement\AdvertisementRepository;

class AdvertisementService
{
    public $repo;

    public function __construct(AdvertisementRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(array $filters = [], string $sort = 'created_at', string $order = 'desc', int $perPage = 10)
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function update($advertisement, array $data)
    {
        return $this->repo->update($advertisement, $data);
    }
}
