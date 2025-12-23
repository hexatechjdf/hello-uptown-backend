<?php

namespace App\Services\Admin\Nightlife;

use App\Repositories\Admin\Nightlife\NightlifeRepository;
use Illuminate\Http\UploadedFile;

class NightlifeService
{
    protected $repo;

    public function __construct(NightlifeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all($filters = [], $sort = 'date', $order = 'desc', $perPage = 10)
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }

    public function find($id, $withRelations = [])
    {
        return $this->repo->find($id, $withRelations);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update($nightlife, array $data)
    {
        return $this->repo->update($nightlife, $data);
    }

         public function delete($nightlife)
    {
        return $this->repo->delete($nightlife);
    }

}
