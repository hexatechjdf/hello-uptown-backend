<?php

namespace App\Http\Requests\Deal;

use App\Helpers\ImageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class StoreDealRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'long_description'  => 'nullable|string',
            'image'             => 'nullable|url',
            'discount'          => 'required|numeric|min:0',
            'original_price'    => 'nullable|numeric|min:0',
            'category_id'       => 'nullable|exists:categories,id',
            'valid_from'        => 'nullable|date',
            'valid_until'       => 'nullable|date|after_or_equal:valid_from',
            'terms_conditions'  => 'nullable|string',
            'is_featured'       => 'boolean',
            'status'            => 'boolean',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            if (!$this->filled('image')) {
                return;
            }
            $error = ImageHelper::validateImageDimensions($this->image,5306,3770);
            if ($error) {
                $validator->errors()->add('image', $error);
            }
        });
    }

}
