<?php

namespace App\Http\Requests\Admin\FarmerMarket;

use App\Helpers\ImageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFarmerMarketRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'             => 'nullable|url',
            'available_vendors' => 'nullable|integer',
            'tags' => 'nullable|array',
            'sub_tags' => 'nullable|array',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'website' => 'nullable|url',
            'map_meta' => 'nullable|array',
            'date' => 'required|date',
            'day' => 'nullable|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'featured' => 'boolean',
            'status' => 'required|in:draft,scheduled,active,expired',
        ];
    }
    public function withValidator(Validator $validator)
    {
        // $validator->after(function ($validator) {
        //     if (!$this->filled('image')) {
        //         return;
        //     }
        //     $error = ImageHelper::validateImageDimensions($this->image,5306,3770);
        //     if ($error) {
        //         $validator->errors()->add('image', $error);
        //     }
        // });
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

?>
