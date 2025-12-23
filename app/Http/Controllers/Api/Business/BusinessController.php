<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\Business\BusinessService;
use App\Resources\Business\BusinessResource;

class BusinessController extends Controller
{
    protected $service;

    public function __construct(BusinessService $service)
    {
        $this->service = $service;
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $business = $user->business;

        if (!$business) {
            return ApiResponse::error('Business not found', null, 404);
        }

        $request->validate([
            'business_name'           => 'sometimes|string|max:255',
            'short_description'       => 'sometimes|string',
            'long_description'        => 'sometimes|string',
            'description'             => 'sometimes|string',
            'category'                => 'sometimes|string',
            'tags'                    => 'sometimes|array',
            'logo'                    => 'sometimes|file|image|max:2048',
            'cover_image'             => 'sometimes|file|image|max:4096',
            'slider_tagline'          => 'sometimes|string',
            'slider_section_text'     => 'sometimes|string',
            'slider_heading_one'      => 'sometimes|string',
            'slider_subheading'       => 'sometimes|string',
            'slider_short_description' => 'sometimes|string',
            'slider_image'            => 'sometimes|file|image|max:4096',
            'image_overlay_heading'   => 'sometimes|string',
            'image_overlay_heading2'  => 'sometimes|string',
            'send_new_deals'          => 'sometimes|boolean',
        ]);

        $updatedBusiness = $this->service->updateProfile($business, $request->all());

        return ApiResponse::resource(new BusinessResource($updatedBusiness),'Business profile updated successfully');
    }
}
