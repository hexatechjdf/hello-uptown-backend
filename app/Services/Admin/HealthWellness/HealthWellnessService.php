<?php

namespace App\Services\Admin\HealthWellness;

use App\Repositories\Admin\HealthWellness\HealthWellnessRepository;
use Illuminate\Http\UploadedFile;
class HealthWellnessService
{
    protected $repo;

    public function __construct(HealthWellnessRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all($filters = [], $sort = 'date', $order = 'desc', $perPage = 10)
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('health_wellness', 'public');
        }
        return $this->repo->create($data);
    }

    public function update($item, array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('health_wellness', 'public');
        }
        return $this->repo->update($item, $data);
    }

    public function delete($item)
    {
        return $this->repo->delete($item);
    }
}
