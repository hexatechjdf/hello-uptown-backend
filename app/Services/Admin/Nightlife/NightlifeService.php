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

    public function create(array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('nightlifes', 'public');
        }
        return $this->repo->create($data);
    }

    public function update($nightlife, array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('nightlifes', 'public');
        }
        return $this->repo->update($nightlife, $data);
    }
}
