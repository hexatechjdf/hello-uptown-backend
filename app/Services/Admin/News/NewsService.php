<?php

namespace App\Services\Admin\News;

use App\Repositories\Admin\News\NewsRepository;
use Illuminate\Http\UploadedFile;

class NewsService
{
    protected $repo;

    public function __construct(NewsRepository $repo)
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
        return $this->repo->create($data);
    }

    public function update($item, array $data)
    {
        return $this->repo->update($item, $data);
    }

      public function delete($item)
    {
        return $this->repo->delete($item);
    }
}
