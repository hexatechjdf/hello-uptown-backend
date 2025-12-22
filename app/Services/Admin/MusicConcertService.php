<?php

namespace App\Services\Admin;

use App\Repositories\Admin\MusicConcertRepository;

class MusicConcertService
{
    public function __construct(
        protected MusicConcertRepository $repo
    ) {}

    public function store(array $data)
    {
        return $this->repo->create($data);
    }

    public function update($concert, array $data)
    {
        return $this->repo->update($concert, $data);
    }
}
