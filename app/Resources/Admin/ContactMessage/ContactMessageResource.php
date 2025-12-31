<?php

namespace App\Resources\Admin\ContactMessage;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactMessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'fullName'  => $this->full_name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'message'   => $this->message,
            'createdAt'=> $this->created_at->toDateTimeString(),
        ];
    }
}
