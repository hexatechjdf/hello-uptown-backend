<?php

namespace App\Http\Requests\Redemption;

use Illuminate\Foundation\Http\FormRequest;
class ListRedemptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'       => 'nullable|string|max:100',
            'status'       => 'nullable|in:redeemed,pending,failed',
            'coupon_id'    => 'nullable|integer|exists:coupons,id',
            'sort_by'      => 'nullable|in:id,redeemed_at,discount_amount',
            'sort_order'   => 'nullable|in:asc,desc',
            'per_page'     => 'nullable|integer|min:1|max:100',
        ];
    }
}
