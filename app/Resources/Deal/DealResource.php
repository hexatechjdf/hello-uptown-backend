<?php

namespace App\Resources\Deal;

use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'business_id'       => $this->business_id,
            'business_logo'       => $this->business?->logo ?? null,
            'title'             => $this->title,
            'short_description' => $this->short_description,
            'long_description'  => $this->long_description,
            'image'             => $this->image,
            'discount'          => $this->discount,
            'original_price'    => $this->original_price,
            'category_id'       => $this->category_id,
            'is_featured'       => $this->is_featured == 1,
            'valid_from'        => $this->valid_from,
            'valid_until'       => $this->valid_until,
            'terms_conditions'  => $this->terms_conditions,
            'status'            => $this->status == 1,
            'included'          => $this->included ?? null,
            'created_at'        => $this->created_at,
        ];
    }
}
