<?php

namespace App\Repositories\Admin\ContactMessage;

use App\Models\ContactMessage;

class ContactMessageRepository
{
    protected $model;

    public function __construct(ContactMessage $model)
    {
        $this->model = $model;
    }

    public function all($filters = [], $perPage = 10)
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('full_name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%");
            });
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function delete(ContactMessage $message)
    {
        return $message->delete();
    }
}
