<?php

namespace App\Resources\Redemption;

use Illuminate\Http\Resources\Json\JsonResource;

class RedemptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'coupon'         => [
                'id'          => $this->coupon->id,
                'title'       => $this->coupon->title,
                'coupon_code' => $this->coupon->coupon_code,
            ],
            'user'           => [
                'id'         => $this->user->id,
                'name'       => $this->user->first_name . ' ' . $this->user->last_name,
                'email'      => $this->user->email,
            ],
            'discount_amount' => $this->discount_amount,
            'redeemed_at'     => $this->redeemed_at ?? null,
            'status'          => $this->status,
        ];
    }
}
