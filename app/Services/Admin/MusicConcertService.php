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

    public function all($filters = [], $sort = 'event_date', $order = 'desc', $perPage = 10): LengthAwarePaginator
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }

    public function find($id): ?MusicConcert
    {
        return $this->repo->find($id);
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
