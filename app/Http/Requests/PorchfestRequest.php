<?php

namespace App\Http\Requests;

use App\Helpers\ImageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class PorchfestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'heading' => 'required|string|max:255',
            'subheading_primary' => 'nullable|string|max:255',
            'subheading_secondary' => 'nullable|string|max:255',
            'description' => 'nullable|string',
             'image'             => 'nullable|url',
            'available_seats' => 'nullable|integer',
            'categories' => 'nullable|array',
            'features' => 'nullable|array',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'event_date' => 'required|date',
            'day' => 'nullable|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'status' => 'required|in:draft,scheduled,active,expired',
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
