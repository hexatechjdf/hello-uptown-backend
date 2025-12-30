<?php

namespace App\Repositories\Admin\Setting;

use App\Models\Setting;

class SettingRepository
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function findByKey(string $key)
    {
        return $this->model->where('key', $key)->first();
    }

    public function upsert(string $key, $value)
    {
        return $this->model->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function deleteByKey(string $key)
    {
        return $this->model->where('key', $key)->delete();
    }
}
