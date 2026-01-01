<?php

namespace App\Services\Admin\HappyHour;

use App\Repositories\Admin\HappyHour\HappyHourRepository;

class HappyHourService
{
    protected $repo;

    public function __construct(HappyHourRepository $repo)
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
            'imageUrl' => 'image',
            'directionLink' => 'direction_link',
            'specialOffer' => 'special_offer',
            'openHours' => 'open_hours',
        ];

        foreach ($fieldMappings as $inputKey => $dbKey) {
            if (array_key_exists($inputKey, $data)) {
                $data[$dbKey] = $data[$inputKey];
                unset($data[$inputKey]);
            }
        }
        $data['featured'] = $data['featured'] ?? false;
        unset($data['slug']);
        if (isset($data['open_hours']) && is_array($data['open_hours'])) {
            $data['open_hours'] = json_encode($data['open_hours']);
        }
        if (isset($data['deals']) && is_array($data['deals'])) {
            $data['deals'] = json_encode($data['deals']);
        }

        return $data;
    }
}
