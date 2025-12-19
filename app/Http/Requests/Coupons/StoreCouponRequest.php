<?php

namespace App\Http\Requests\Coupons;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add business ownership check later
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'coupon_code' => 'required|string|max:50|unique:coupons,coupon_code',

            'short_description' => 'nullable|string|max:255',
            'long_description' => 'nullable|string',

            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',

            'category_id' => 'nullable|integer',

            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',

            'usage_limit_per_user' => 'required|integer|min:1',
            'minimum_spend' => 'nullable|numeric|min:0',

            'terms_conditions' => 'nullable|string',

            'is_active' => 'boolean',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422));
    }
}
