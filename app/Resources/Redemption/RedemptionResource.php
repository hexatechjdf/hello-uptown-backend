<?php

namespace App\Resources\Redemption;

use Illuminate\Http\Resources\Json\JsonResource;

class RedemptionResource extends JsonResource
{
    public function toArray($request)
    {
        $isCoupon = $this->type === 'coupon';
        $parentItem = $this->item; // Using the item accessor from the model
        $customer = $this->customer;
        return [
            'id'               => $this->id,
            'type'             => $this->type, // 'coupon' or 'deal'
            'parent_id'        => $this->parent_id,
            'item'             => $parentItem ? [
                'id'            => $parentItem->id,
                'title'         => $parentItem->title,
                'description'   => $parentItem->short_description ?? $parentItem->description ?? null,
                'image'         => $parentItem->image ?? null,
                'valid_from'    => $parentItem->valid_from ?? null,
                'valid_until'   => $parentItem->valid_until ?? null,
                // Coupon specific fields
                'coupon_code'   => $isCoupon ? $parentItem->coupon_code : null,
                // 'discount_type' => $isCoupon ? $parentItem->discount_type : null,
                // 'discount_value'=> $isCoupon ? $parentItem->discount_value : null,
                // 'minimum_spend' => $isCoupon ? $parentItem->minimum_spend : null,
                // Deal specific fields
                // 'discount'      => !$isCoupon ? $parentItem->discount : null,
                // 'original_price'=> !$isCoupon ? $parentItem->original_price : null,
                // 'is_featured'   => !$isCoupon ? $parentItem->is_featured : null,
                // 'is_active'     => $isCoupon ? $parentItem->is_active : null,
            ] : null,
            'customer'         => $customer ? [
                'id'           => $customer->id,
                'name'         => $customer->name,
                'email'        => $customer->email,
                'phone'        => $customer->phone ?? null,
            ] : null,
            'business'         => $this->business ? [
                'id'           => $this->business->id,
                'name'         => $this->business->business_name,
                'logo'         => $this->business->logo ?? null,
            ] : null,
            'discount_amount'  => $this->discount_amount,
            'redeemed_at'      => $this->redeemed_at ? $this->redeemed_at->format('Y-m-d H:i:s') : null,
            'redeemed_at_formatted' => $this->redeemed_at ? $this->redeemed_at->format('F j, Y g:i A') : null,
            'status'           => $this->status,
            'created_at'       => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'created_at_formatted' => $this->created_at ? $this->created_at->format('F j, Y') : null,
            'distance_in_meters' => $this->distance_in_meters ?? null,
        ];
    }
}
