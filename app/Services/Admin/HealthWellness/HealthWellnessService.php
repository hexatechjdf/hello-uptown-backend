<?php

namespace App\Services\Admin\HealthWellness;

use App\Repositories\Admin\HealthWellness\HealthWellnessRepository;

class HealthWellnessService
{
    protected $repo;

    public function __construct(HealthWellnessRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all($filters = [], $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        $data = $this->prepareData($data);
        return $this->repo->create($data);
    }

    public function update($item, array $data)
    {
        $data = $this->prepareData($data);
        return $this->repo->update($item, $data);
    }

    public function delete($item)
    {
        return $this->repo->delete($item);
    }

    private function prepareData(array $data): array
    {
        $fieldMappings = [
            'providerName' => 'provider_name',
            'directionLink' => 'direction_link',
        ];

        foreach ($fieldMappings as $inputKey => $dbKey) {
            if (array_key_exists($inputKey, $data)) {
                $data[$dbKey] = $data[$inputKey];
                unset($data[$inputKey]);
            }
        }

        // Set default values
        $data['featured'] = $data['featured'] ?? false;

        unset($data['slug']);
        if (isset($data['duration']) && is_array($data['duration'])) {
            $data['duration'] = json_encode($data['duration']);
        }
        if (isset($data['price']) && is_array($data['price'])) {
            $data['price'] = json_encode($data['price']);
        }

        if (isset($data['time']) && is_array($data['time'])) {
            $data['time'] = json_encode($data['time']);
        }

        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = array_filter($data['features']);
            if (!empty($data['features'])) {
                $data['features'] = json_encode($data['features']);
            } else {
                $data['features'] = null;
            }
        }

        return $data;
    }
}
