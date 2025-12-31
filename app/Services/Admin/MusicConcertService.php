<?php

namespace App\Services\Admin;

use App\Repositories\Admin\MusicConcertRepository;
use App\Models\MusicConcert;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MusicConcertService
{
    public function __construct(
        protected MusicConcertRepository $repo
    ) {}

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return $this->repo->paginate($filters);
    }
    public function store(array $data): MusicConcert
    {
        return $this->repo->create($data);
    }

    public function update(MusicConcert $concert, array $data): MusicConcert
    {
        return $this->repo->update($concert, $data);
    }
    public function delete(MusicConcert $concert): bool
    {
        return $this->repo->delete($concert);
    }
}
