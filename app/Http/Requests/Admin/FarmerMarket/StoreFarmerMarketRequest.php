<?php
namespace App\Http\Requests\Admin\FarmerMarket;

use Illuminate\Foundation\Http\FormRequest;
class StoreFarmerMarketRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'heading' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
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
}

?>