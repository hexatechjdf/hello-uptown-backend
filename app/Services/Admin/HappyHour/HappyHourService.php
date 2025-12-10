<?php

namespace App\Services\Admin\HappyHour;

use App\Repositories\Admin\HappyHour\HappyHourRepository;
use Illuminate\Http\UploadedFile;

class HappyHourService
{
    protected $repo;

    public function __construct(HappyHourRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('happy_hours', 'public');
        }
        return $this->repo->create($data);
    }

    public function update($item, array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('happy_hours', 'public');
        }
        return $this->repo->update($item, $data);
    }
}
