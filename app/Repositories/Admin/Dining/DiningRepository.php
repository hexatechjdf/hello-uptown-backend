<?php

namespace App\Repositories\Admin\Dining;

use App\Models\Dining;

class DiningRepository
{
    protected $model;

    public function __construct(Dining $model)
    {
        $this->model = $model;
    }

    public function all($filters = [], $sort = 'date', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where('heading', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['status'])) {
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

    public function update(Dining $dining, array $data)
    {
        $dining->update($data);
        return $dining->fresh();
    }

    public function delete(Dining $dining)
    {
        return $dining->delete();
    }
}
