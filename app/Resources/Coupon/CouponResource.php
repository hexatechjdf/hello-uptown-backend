<?php

namespace App\Resources\Coupon;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'title'                 => $this->title,
            'coupon_code'           => $this->coupon_code,
            'businessLogo'          => $this->business?->logo ?? null,
            'image'                 => $this->image ?? null,
            'short_description'     => $this->short_description,
            'long_description'      => $this->long_description,
            'discount_type'         => $this->discount_type,
            'discount_value'        => $this->discount_value,
            'category_id'           => $this->category_id,
            'valid_from'            => $this->valid_from,
            'valid_until'           => $this->valid_until,
            'usage_limit_per_user'  => $this->usage_limit_per_user,
            'minimum_spend'         => $this->minimum_spend,
            'terms_conditions'      => $this->terms_conditions,
            'is_active'             => $this->is_active == 1,
            'created_at'            => $this->created_at,
        ];
    }
}
