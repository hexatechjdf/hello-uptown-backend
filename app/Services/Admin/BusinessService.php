<?php

namespace App\Services\Admin;

use App\Repositories\Admin\BusinessRepository;
use Illuminate\Support\Str;

class BusinessService
{
    public function __construct(
        protected BusinessRepository $repo
    ) {}

    public function list(array $filters)
    {
        $query = $this->repo->query();

        if (!empty($filters['search'])) {
            $query->where('business_name', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['business_name']);
        return $this->repo->create($data);
    }

    public function update($business, array $data)
    {
        if (!empty($data['business_name'])) {
            $data['slug'] = Str::slug($data['business_name']);
        }

        return $this->repo->update($business, $data);
    }
    public function getByUserId($userId)
    {
        return $this->repo->findByField('user_id', $userId)->first();
    }
}
