<?php

namespace App\Services\Admin\Dining;

use App\Repositories\Admin\Dining\DiningRepository;

class DiningService
{
    public $repo;

    public function __construct(DiningRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getAll(array $filters = [], string $sort = 'created_at', string $order = 'desc', int $perPage = 10)
    {
        return $this->repo->all($filters, $sort, $order, $perPage);
    }
    public function create(array $data)
    {
        $data = $this->prepareData($data);
        return $this->repo->create($data);
    }
    public function find($id)
    {
        return $this->repo->find($id);
    }
    public function update($dining, array $data)
    {
        $data = $this->prepareData($data);
        return $this->repo->update($dining, $data);
    }
    private function prepareData(array $data): array
    {
        // Map camelCase to snake_case for database
        $fieldMappings = [
            'isFeatured' => 'is_featured',
            'directionLink' => 'direction_link',
            'cuisineTypes' => 'cuisine_types',
            'price' => 'price_range',
        ];

        foreach ($fieldMappings as $inputKey => $dbKey) {
            if (array_key_exists($inputKey, $data)) {
                $data[$dbKey] = $data[$inputKey];
                unset($data[$inputKey]);
            }
        }

        // Set default values
        $data['is_featured'] = $data['is_featured'] ?? false;

        // Remove slug from data - it will be auto-generated from title
        unset($data['slug']);

        // Convert time field to JSON
        if (isset($data['time']) && is_array($data['time'])) {
            $data['time'] = json_encode($data['time']);
        }

        // Convert arrays to JSON if they're arrays
        if (isset($data['cuisine_types']) && is_array($data['cuisine_types'])) {
            $data['cuisine_types'] = array_filter($data['cuisine_types']);
            if (!empty($data['cuisine_types'])) {
                $data['cuisine_types'] = json_encode($data['cuisine_types']);
            } else {
                $data['cuisine_types'] = null;
            }
        }

        return $data;
    }
}
