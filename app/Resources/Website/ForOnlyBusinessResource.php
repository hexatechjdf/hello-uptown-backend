<?php

namespace App\Resources\Website;

use Illuminate\Http\Resources\Json\JsonResource;

class ForOnlyBusinessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => (string) $this->id,
            'name'        => $this->business_name,
            'slug'        => $this->slug,
            'description' => $this->description ?? $this->short_description ?? $this->long_description,
            'image'       => $this->cover_image ?? $this->slider_image,
            'logo'        => $this->logo,
            'category'    => $this->category->name ?? null,
            'tags'        => $this->tags ?? [],
            'address'     => $this->address,
        ];
    }
}
