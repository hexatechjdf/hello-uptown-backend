<?php

namespace App\Services\Admin\Setting;

use App\Repositories\Admin\Setting\SettingRepository;

class SettingService
{
    public $repo;

    public function __construct(SettingRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getByKey(string $key)
    {
        return $this->repo->findByKey($key);
    }

    public function save(string $key, $value)
    {
        return $this->repo->upsert($key, $value);
    }

    public function delete(string $key)
    {
        return $this->repo->deleteByKey($key);
    }
}
