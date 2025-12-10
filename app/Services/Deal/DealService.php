<?php

namespace App\Services;

use App\Models\Deal;
use App\Repositories\DealRepository;
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

        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('deals', 'public');
        }

        return $this->repo->create($data);
    }

    public function update(Deal $deal, array $data)
    {
        if (isset($data['image'])) {
            if ($deal->image) Storage::disk('public')->delete($deal->image);
            $data['image'] = $data['image']->store('deals', 'public');
        }

        return $this->repo->update($deal, $data);
    }

    public function delete(Deal $deal)
    {
        if ($deal->image) Storage::disk('public')->delete($deal->image);

        return $this->repo->delete($deal);
    }
}
