<?php
use Illuminate\Foundation\Http\FormRequest;
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
}


?>