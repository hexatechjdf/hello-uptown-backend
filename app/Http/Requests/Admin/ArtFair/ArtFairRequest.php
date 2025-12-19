<?php

namespace App\Http\Requests\Admin\ArtFair;

use App\Helpers\ImageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class ArtFairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading'           => 'required|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|url',
            'available_artist'  => 'nullable|integer',
            'art_categories'    => 'nullable|array',
            'event_features'    => 'nullable|array',
            'admission_type'    => 'required|in:free,paid',
            'admission_amount'  => 'required_if:admission_type,paid|nullable|numeric|min:0',
            'address'           => 'nullable|string',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'event_date'        => 'required|date',
            'day'               => 'nullable|string',
            'start_time'        => 'nullable',
            'end_time'          => 'nullable',
            'status'            => 'required|in:draft,scheduled,active,expired',
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
        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422));
        }
}
