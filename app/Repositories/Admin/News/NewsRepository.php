<?php

namespace App\Repositories\Admin\News;

use App\Models\News;

class NewsRepository
{
    protected $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function all($filters = [], $sort = 'published_at', $order = 'desc', $perPage = 10)
    {
        $query = $this->model->with('category');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%")
                  ->orWhere('author', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['featured'])) {
            $query->where('featured', filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query->orderBy($sort, $order)->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->with('category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(News $item, array $data)
    {
        $item->update($data);
        return $item->fresh();
    }

    public function delete(News $item)
    {
        return $item->delete();
    }
}
