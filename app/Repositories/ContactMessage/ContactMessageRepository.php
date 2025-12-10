<?php

namespace App\Repositories\ContactMessage;

use App\Models\ContactMessage;

class ContactMessageRepository
{
    protected $model;

    public function __construct(ContactMessage $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
