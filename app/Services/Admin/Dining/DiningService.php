<?php

namespace App\Services\Admin\Dining;

use App\Repositories\Admin\Dining\DiningRepository;
use Illuminate\Http\UploadedFile;

class DiningService
{
    public $repo; // Changed from protected to public

    public function __construct(DiningRepository $repo)
    {
        $this->repo = $repo;
    }
   public function getAll(array $filters = [], string $sort = 'date', string $order = 'desc', int $perPage = 10)
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }
    public function create(array $data)
    {
        return $this->repo->create($data);
    }
    public function find($id)
    {
        return $this->repo->find($id);
    }
    public function update($dining, array $data)
    {
        return $this->repo->update($dining, $data);
    }
}
