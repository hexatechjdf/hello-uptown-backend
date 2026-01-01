<?php

namespace App\Http\Requests\Admin\FarmerMarket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFarmerMarketRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'available_vendors' => 'nullable|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'direction_link' => 'nullable|url',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'website' => 'nullable|url',
            'ticket_link' => 'nullable|url',
            'schedule' => 'nullable|array',
            'next_market_date' => 'nullable|date',
            'featured' => 'boolean',
            'status' => 'required|in:draft,scheduled,active,expired',
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
