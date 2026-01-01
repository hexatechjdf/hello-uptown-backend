<?php
namespace App\Services\Admin\ArtFair;

use Illuminate\Support\Str;

class ArtFairService
{
    public function prepareData(array $data): array
    {
        // Handle admission amount for free events
        if ($data['admission_type'] === 'free') {
            $data['admission_amount'] = null;
        }

        // Ensure artist_count and available_artist are set and synchronized
        if (isset($data['artist_count'])) {
            $data['available_artist'] = $data['artist_count'];
        } elseif (isset($data['available_artist'])) {
            $data['artist_count'] = $data['available_artist'];
        } else {
            $data['artist_count'] = 0;
            $data['available_artist'] = 0;
        }

        // Set default values
        $data['featured'] = $data['featured'] ?? false;

        // Remove slug from data - it will be auto-generated from heading
        unset($data['slug']);

        // Convert arrays to JSON if they're arrays
        if (isset($data['art_categories']) && is_array($data['art_categories'])) {
            $data['art_categories'] = array_filter($data['art_categories']);
            if (!empty($data['art_categories'])) {
                $data['art_categories'] = json_encode($data['art_categories']);
            } else {
                $data['art_categories'] = null;
            }
        }

        if (isset($data['event_features']) && is_array($data['event_features'])) {
            $data['event_features'] = array_filter($data['event_features']);
            if (!empty($data['event_features'])) {
                $data['event_features'] = json_encode($data['event_features']);
            } else {
                $data['event_features'] = null;
            }
        }

        return $data;
    }
}
