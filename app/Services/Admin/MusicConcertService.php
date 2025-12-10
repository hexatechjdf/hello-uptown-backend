<?php

namespace App\Services\Admin;

use App\Repositories\Admin\MusicConcertRepository;
use Illuminate\Http\UploadedFile;

class MusicConcertService
{
    public function __construct(
        protected MusicConcertRepository $repo
    ) {}

    public function store(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('concerts', 'public');
        }

        return $this->repo->create($data);
    }

    public function update($concert, array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('concerts', 'public');
        }

        return $this->repo->update($concert, $data);
    }
    public function handleImage(array &$data)
    {
        if (!empty($data['image'])) {
            $data['image'] = $data['image']->store('concerts', 'public');
        }
    }
}
