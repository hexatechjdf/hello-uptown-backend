<?php

namespace App\Repositories\Admin;

use App\Models\MusicConcert;

class MusicConcertRepository
{
   public function query()
    {
        return MusicConcert::query();
    }

    public function create(array $data)
    {
        return MusicConcert::create($data);
    }

    public function update(MusicConcert $concert, array $data)
    {
        $concert->update($data);
        return $concert;
    }

    public function delete(MusicConcert $concert)
    {
        return $concert->delete();
    }
}
