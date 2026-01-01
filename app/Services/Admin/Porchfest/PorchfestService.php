<?php

namespace App\Services\Admin\Porchfest;

use Illuminate\Support\Str;

class PorchfestService
{
    public function store(array $data)
    {
        $data = $this->prepareData($data);
        return app(\App\Repositories\Admin\Porchfest\PorchfestRepository::class)->create($data);
    }

    public function update($porchfest, array $data)
    {
        $data = $this->prepareData($data);
        return app(\App\Repositories\Admin\Porchfest\PorchfestRepository::class)->update($porchfest, $data);
    }

    private function prepareData(array $data): array
    {
        // Map camelCase to snake_case for database
        $fieldMappings = [
            'isFeatured' => 'is_featured',
            'directionLink' => 'direction_link',
            'eventFeatures' => 'event_features',
        ];

        foreach ($fieldMappings as $inputKey => $dbKey) {
            if (array_key_exists($inputKey, $data)) {
                $data[$dbKey] = $data[$inputKey];
                unset($data[$inputKey]);
            }
        }

        // Set default values
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['attendees'] = $data['attendees'] ?? 0;
        $data['available_seats'] = $data['available_seats'] ?? 0;

        // Remove slug from data - it will be auto-generated from title
        unset($data['slug']);

        // Convert time field to JSON
        if (isset($data['time']) && is_array($data['time'])) {
            $data['time'] = json_encode($data['time']);
        }

        // Convert arrays to JSON if they're arrays
        $arrayFields = ['genre', 'event_features'];
        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = array_filter($data[$field]);
                if (!empty($data[$field])) {
                    $data[$field] = json_encode($data[$field]);
                } else {
                    $data[$field] = null;
                }
            }
        }

        return $data;
    }
}
