<?php

namespace App\Services\Admin\Porchfest;

use App\Repositories\Admin\Porchfest\PorchfestRepository;
use Illuminate\Http\UploadedFile;

class PorchfestService
{
    public function __construct(private PorchfestRepository $repo) {}

    public function store(array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('porchfest', 'public');
        }

        return $this->repo->create($data);
    }

    public function update($porchfest, array $data)
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('porchfest', 'public');
        } else {
            unset($data['image']);
        }

        return $this->repo->update($porchfest, $data);
    }
}

?>