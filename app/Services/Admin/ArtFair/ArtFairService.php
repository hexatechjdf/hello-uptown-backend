<?php
namespace App\Services\Admin\ArtFair;
class ArtFairService
{
    public function prepareData(array $data): array
    {
        if ($data['admission_type'] === 'free') {
            $data['admission_amount'] = null;
        }
        return $data;
    }
}
?>
