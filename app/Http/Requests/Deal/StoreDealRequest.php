<?php

namespace App\Http\Requests\Deal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Http;

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

        try {
            $response = Http::timeout(5)->get($this->image);
            if (!$response->successful()) {
                $validator->errors()->add('image', 'Unable to download image from the provided URL.');
                return;
            }
            $tempPath = tempnam(sys_get_temp_dir(), 'deal_img_');
            file_put_contents($tempPath, $response->body());

            $imageInfo = getimagesize($tempPath);

            if ($imageInfo === false) {
                unlink($tempPath);
                $validator->errors()->add('image', 'The provided URL does not contain a valid image.');
                return;
            }

            [$width, $height] = $imageInfo;

            // REQUIRED DIMENSIONS
            $requiredWidth  = 5306;
            $requiredHeight = 3770;

            if ($width !== $requiredWidth || $height !== $requiredHeight) {
                $validator->errors()->add('image',"Image dimensions must be {$requiredWidth}x{$requiredHeight}px.");
            }
            // unlink($tempPath);

        } catch (\Exception $e) {
            $validator->errors()->add('image', 'Failed to validate image from the provided URL.');
        }
    });
}


}
