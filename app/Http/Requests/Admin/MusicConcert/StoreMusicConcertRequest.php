<?php

use App\Helpers\ImageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreMusicConcertRequest extends FormRequest
{
    public function rules()
    {
        return [
            'main_heading' => 'required|string|max:255',
            'sub_heading' => 'nullable|string|max:255',
            'event_description' => 'required|string',
            'image'             => 'nullable|url',
            'available_attendees' => 'nullable|integer|min:1',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'place_id' => 'nullable|string',
            'website' => 'nullable|url',
            'status' => 'required|in:draft,scheduled,active,expired',
            'event_date' => 'nullable|date',
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
?>
