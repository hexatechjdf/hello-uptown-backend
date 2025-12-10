<?php

namespace App\Repositories\Admin\HappyHour;

use App\Models\HappyHour;

class HappyHourRepository
{
    protected $model;

    public function __construct(HappyHour $model)
    {
        $this->model = $model;
    }

    public function all($filters = [], $sort = 'date', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where('heading', 'like', "%{$filters['search']}%")
                  ->orWhere('special_offer_text', 'like', "%{$filters['search']}%");
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

    public function update(HappyHour $item, array $data)
    {
        $item->update($data);
        return $item->fresh();
    }

    public function delete(HappyHour $item)
    {
        return $item->delete();
    }
}
