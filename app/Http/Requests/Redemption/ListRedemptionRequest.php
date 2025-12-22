<?php

namespace App\Http\Requests\Redemption;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            'search'     => 'nullable|string|max:100',
            'status'     => 'nullable|string',
            // 'coupon_id'  => 'nullable|integer',
            'sort'       => 'nullable|in:newest,oldest',
            'time'       => 'nullable|in:all,today,this_week,this_month',
            'per_page'   => 'nullable|integer|min:1|max:100',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => false,
            'message' => 'Validation error',
            'errors'  => $validator->errors()
        ], 422));
    }
}
