<?php
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdateBusinessRequest extends FormRequest
{
     public function authorize(): bool
    {
        return true;
    }
    public function rules()
    {
        return [
            'tagline' => 'nullable|string',
            'heading_one' => 'nullable|string',
            'subheading' => 'nullable|string',
            'short_description' => 'nullable|string',
            'slider_text_section' => 'nullable|string',
            'image_overlay_heading' => 'nullable|string',
            'image_overlay_heading2' => 'nullable|string',
            'text1' => 'nullable|string',
            'value1' => 'nullable|string',
            'text2' => 'nullable|string',
            'value2' => 'nullable|string',
            'text3' => 'nullable|string',
            'value3' => 'nullable|string',
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


?>
