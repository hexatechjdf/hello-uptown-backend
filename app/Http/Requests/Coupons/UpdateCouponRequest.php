<?php

namespace App\Http\Requests\Coupons;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',

            'coupon_code' => 'sometimes|string|max:50|unique:coupons,coupon_code,' . $this->coupon->id,

            'short_description' => 'nullable|string|max:255',
            'long_description' => 'nullable|string',

            'discount_type' => 'sometimes|in:fixed,percentage',
            'discount_value' => 'sometimes|numeric|min:0',

            'category_id' => 'nullable|integer',

            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',

            'usage_limit_per_user' => 'sometimes|integer|min:1',
            'minimum_spend' => 'nullable|numeric|min:0',

            'terms_conditions' => 'nullable|string',

            'is_active' => 'boolean',
        ];
    }
}
