<?php

namespace App\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Resources\Business\BusinessResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'firstName'  => $this->first_name,
            'lastName'   => $this->last_name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'notifications'  => $this->notifications == 1,
            'role'       => $this->roles->pluck('name')->first(),
            'avatar'     => $this->profile ?? null,
            'createdAt'  => $this->created_at,
            'businessId' =>  $this->business_id ?? ($this->relationLoaded('business') && $this->business ? $this->business->id : null),
            'business'    => $this->whenLoaded('business', fn() => new BusinessResource($this->business)),

        ];
    }
}
