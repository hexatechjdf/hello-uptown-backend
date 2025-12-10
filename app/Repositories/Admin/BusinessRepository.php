<?php

namespace App\Repositories\Admin;

use App\Models\Business;

class BusinessRepository
{
    public function query()
    {
        return Business::with('user');
    }

    public function find(int $id)
    {
        return Business::with('user')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Business::create($data);
    }

    public function update(Business $business, array $data)
    {
        $business->update($data);
        return $business->fresh();
    }

    public function delete(Business $business)
    {
        return $business->delete();
    }
}
