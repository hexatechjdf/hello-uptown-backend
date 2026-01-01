<?php

namespace App\Services\Admin\Nightlife;

use App\Repositories\Admin\Nightlife\NightlifeRepository;

class NightlifeService
{
    protected $repo;

    public function __construct(NightlifeRepository $repo)
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

    public function update($nightlife, array $data)
    {
        $data = $this->prepareData($data);
        return $this->repo->update($nightlife, $data);
    }

    public function delete($nightlife)
    {
        return $this->repo->delete($nightlife);
    }

    private function prepareData(array $data): array
    {
        $fieldMappings = [
            'venueName' => 'venue_name',
            'directionLink' => 'direction_link',
        ];

        foreach ($fieldMappings as $inputKey => $dbKey) {
            if (array_key_exists($inputKey, $data)) {
                $data[$dbKey] = $data[$inputKey];
                unset($data[$inputKey]);
            }
        }

        $data['featured'] = $data['featured'] ?? false;
        unset($data['slug']);

        if (isset($data['price']) && is_array($data['price'])) {
            $data['price'] = json_encode($data['price']);
        }

        if (isset($data['time']) && is_array($data['time'])) {
            $data['time'] = json_encode($data['time']);
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            $data['tags'] = array_filter($data['tags']);
            if (!empty($data['tags'])) {
                $data['tags'] = json_encode($data['tags']);
            } else {
                $data['tags'] = null;
            }
        }

        return $data;
    }
}
