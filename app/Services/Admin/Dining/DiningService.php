<?php

namespace App\Services\Dining;

use App\Repositories\Dining\DiningRepository;
use Illuminate\Http\UploadedFile;

class DiningService
{
    protected $repo;

    public function __construct(DiningRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('dinings', 'public');
        }
        return $this->repo->create($data);
    }

    public function update($dining, array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('dinings', 'public');
        }
        return $this->repo->update($dining, $data);
    }
}
