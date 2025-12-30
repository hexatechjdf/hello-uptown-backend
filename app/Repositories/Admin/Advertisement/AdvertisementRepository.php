<?php

namespace App\Repositories\Admin\Advertisement;

use App\Models\Advertisement;

class AdvertisementRepository
{
    protected $model;

    public function __construct(Advertisement $model)
    {
        $this->model = $model;
    }

    public function all($filters = [], $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy($sort, $order)->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Advertisement $advertisement, array $data)
    {
        $advertisement->update($data);
        return $advertisement->fresh();
    }

    public function delete(Advertisement $advertisement)
    {
        return $advertisement->delete();
    }
}
