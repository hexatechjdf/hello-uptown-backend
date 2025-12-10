<?php

namespace App\Services\Business;

use App\Repositories\Business\BusinessRepository;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class BusinessService
{
    protected $repo;

    public function __construct(BusinessRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Update business profile
     */
    public function updateProfile($business, array $data)
    {
        $files = ['logo', 'cover_image', 'slider_image'];
        foreach ($files as $fileKey) {
            if (!empty($data[$fileKey]) && $data[$fileKey] instanceof UploadedFile) {
                $data[$fileKey] = $data[$fileKey]->store('business', 'public');
            } else {
                unset($data[$fileKey]);
            }
        }

        if (!empty($data['business_name'])) {
            $data['slug'] = Str::slug($data['business_name']);
        }

        $this->repo->update($business, $data);

        return $business->fresh();
    }
}
