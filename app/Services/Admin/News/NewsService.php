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

    public function create(array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('news', 'public');
        }
        return $this->repo->create($data);
    }

    public function update($item, array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('news', 'public');
        }
        return $this->repo->update($item, $data);
    }
}
