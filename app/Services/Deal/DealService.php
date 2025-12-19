<?php

namespace App\Services\Deal;

use App\Models\Deal;
use App\Repositories\Deal\DealRepository;
use Illuminate\Support\Facades\Storage;

class DealService
{
    public function __construct(protected DealRepository $repo) {}

    public function list(array $filters, $businessId)
    {
        return $this->repo->search($filters, $businessId);
    }

    public function create(array $data, $businessId)
    {
        $data['business_id'] = $businessId;
        return $this->repo->create($data);
    }

    public function update(Deal $deal, array $data)
    {
        return $this->repo->update($deal, $data);
    }

    public function delete(Deal $deal)
    {
        return $this->repo->delete($deal);
    }
}
