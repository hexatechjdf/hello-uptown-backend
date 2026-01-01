<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'imageUrl' => 'nullable|url',
            'featured' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
            'articleUrl' => 'nullable|url|max:255',
            'publishedAt' => 'nullable|date',
            'status' => 'required|in:active,draft,expired,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category is invalid or not of type news.',
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
