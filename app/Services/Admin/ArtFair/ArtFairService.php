<?php
namespace App\Services\Admin\ArtFair;
class ArtFairService
{
    public function prepareData(array $data): array
    {
        if (!empty($data['image'])) {
            $data['image'] = $data['image']->store('art-fairs', 'public');
        }

        if ($data['admission_type'] === 'free') {
            $data['admission_amount'] = null;
        }

        return $data;
    }
}
?>