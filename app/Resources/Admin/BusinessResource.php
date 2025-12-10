<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminBusinessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->business_name,
            'slug'          => $this->slug,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'category'      => $this->category,
            'status'        => $this->status,
            'owner' => [
                'id'    => $this->user->id,
                'name'  => $this->user->first_name.' '.$this->user->last_name,
                'email' => $this->user->email,
            ],
            'createdAt'     => $this->created_at,
        ];
    }
}
