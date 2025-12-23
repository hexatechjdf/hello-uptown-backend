<?php


namespace App\Http\Requests\Admin\NightLife;

use App\Helpers\ImageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class NightlifeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Admin middleware handles protection
    }

    public function rules(): array
    {
        return [
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'             => 'nullable|url',
            'main_tags' => 'nullable|array',
            'main_tags.*' => 'string|max:50',
            'header_tags' => 'nullable|array',
            'header_tags.*' => 'string|max:50',
            'actual_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'date' => 'nullable|date',
            'day' => 'nullable|string|max:20',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:active,draft,expired',
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
