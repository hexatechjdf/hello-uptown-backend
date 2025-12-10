<?php

namespace App\Http\Requests\Deal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'             => 'sometimes|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'long_description'  => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
            'discount'          => 'sometimes|numeric|min:0',
            'original_price'    => 'nullable|numeric|min:0',
            'category_id'       => 'nullable|exists:categories,id',
            'valid_from'        => 'nullable|date',
            'valid_until'       => 'nullable|date|after_or_equal:valid_from',
            'terms_conditions'  => 'nullable|string',
            'is_featured'       => 'boolean',
            'status'            => 'boolean',
        ];
    }
}
