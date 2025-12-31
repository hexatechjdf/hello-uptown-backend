<?php

namespace App\Services\Admin\ContactMessage;

use App\Repositories\Admin\ContactMessage\ContactMessageRepository;

class ContactMessageService
{
    public $repo;

    public function __construct(ContactMessageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(array $filters = [], int $perPage = 10)
    {
        return $this->repo->all($filters, $perPage);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function delete($message)
    {
        return $this->repo->delete($message);
    }
}
