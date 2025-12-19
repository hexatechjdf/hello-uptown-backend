<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BusinessService;
use App\Resources\Admin\BusinessResource;
use App\Helpers\ApiResponse;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function __construct(
        protected BusinessService $service
    ) {}

    public function getProfile(Request $request)
    {
        $business = $this->service->getByUserId($request->user()->id);

        return ApiResponse::resource(new BusinessResource($business->load('user')),'Business profile fetched successfully');
    }
    public function index(Request $request)
    {
        $businesses = $this->service->list($request->all());

        return ApiResponse::collection(
            BusinessResource::collection($businesses),
            'Businesses fetched successfully'
        );
    }

   public function store(Request $request)
    {
        $data = $request->validate([
            // Required
            'user_id'        => 'required|exists:users,id',
            'business_name'  => 'required|string|max:255',
            'slug'           => 'required|string|max:255|unique:businesses,slug',

            // Descriptions
            'short_description' => 'nullable|string',
            'long_description'  => 'nullable|string',
            'description'       => 'nullable|string',

            // Category & Tags
            'category_id' => 'nullable|exists:categories,id',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string',

            // Branding
            'logo'        => 'nullable|string',
            'cover_image' => 'nullable|string',

            // Contact & Location
            'address'            => 'nullable|string',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
            'redemption_radius'  => 'nullable|integer',
            'phone'              => 'nullable|string|max:50',
            'email'              => 'nullable|email',
            'website'            => 'nullable|url',
            'opening_hours'      => 'nullable|string',

            // Social Links
            'facebook_link'  => 'nullable|url',
            'instagram_link' => 'nullable|url',
            'twitter_link'   => 'nullable|url',

            // Slider Settings
            'slider_tagline'              => 'nullable|string',
            'slider_section_text'         => 'nullable|string',
            'slider_heading_one'          => 'nullable|string',
            'slider_subheading'           => 'nullable|string',
            'slider_short_description'    => 'nullable|string',
            'slider_image'                => 'nullable|string',
            'image_overlay_heading'       => 'nullable|string',
            'image_overlay_heading2'      => 'nullable|string',

            'slider_text1'        => 'nullable|string',
            'slider_text1_value'  => 'nullable|string',
            'slider_text2'        => 'nullable|string',
            'slider_text2_value'  => 'nullable|string',
            'slider_text3'        => 'nullable|string',
            'slider_text3_value'  => 'nullable|string',

            // Notification & Status
            'send_new_deals' => 'nullable|boolean',
            'status'         => 'nullable|boolean',
        ]);

        $business = $this->service->create($data);

        return ApiResponse::resource(
            new BusinessResource($business),
            'Business created successfully',
            [],
            201
        );
    }

    public function show(Business $business)
    {
        return ApiResponse::resource(
            new BusinessResource($business->load('user')),
            'Business details'
        );
    }

    public function update(Request $request, Business $business)
    {
        $data = $request->validate([
            'business_name' => 'sometimes|string|max:255',
            'slug'          => 'sometimes|string|max:255|unique:businesses,slug,' . $business->id,
            // Descriptions
            'short_description' => 'sometimes|string',
            'long_description'  => 'sometimes|string',
            'description'       => 'sometimes|string',
            // Category & Tags
            'category_id' => 'sometimes|exists:categories,id',
            'tags'        => 'sometimes|array',
            'tags.*'      => 'string',

            // Branding
            'logo'        => 'sometimes|string',
            'cover_image' => 'sometimes|string',
            // Contact & Location
            'address'           => 'sometimes|string',
            'latitude'          => 'sometimes|numeric',
            'longitude'         => 'sometimes|numeric',
            'redemption_radius' => 'sometimes|integer',
            'phone'             => 'sometimes|string|max:50',
            'email'             => 'sometimes|email',
            'website'           => 'sometimes|url',
            'opening_hours'     => 'sometimes|string',

            // Social Links
            'facebook_link'  => 'sometimes|url',
            'instagram_link' => 'sometimes|url',
            'twitter_link'   => 'sometimes|url',

            // Slider Settings
            'slider_tagline'           => 'sometimes|string',
            'slider_section_text'      => 'sometimes|string',
            'slider_heading_one'       => 'sometimes|string',
            'slider_subheading'        => 'sometimes|string',
            'slider_short_description' => 'sometimes|string',
            'slider_image'             => 'sometimes|string',
            'image_overlay_heading'    => 'sometimes|string',
            'image_overlay_heading2'   => 'sometimes|string',

            'slider_text1'       => 'sometimes|string',
            'slider_text1_value' => 'sometimes|string',
            'slider_text2'       => 'sometimes|string',
            'slider_text2_value' => 'sometimes|string',
            'slider_text3'       => 'sometimes|string',
            'slider_text3_value' => 'sometimes|string',

            // Notification & Status
            'send_new_deals' => 'sometimes|boolean',
            'status'         => 'sometimes|boolean',
        ]);

        $this->service->update($business, $data);
        $business->refresh();

        return ApiResponse::resource(
            new BusinessResource($business),
            'Business updated successfully'
        );
    }

    public function destroy(Business $business)
    {
        $business->delete();

        return ApiResponse::success(null, 'Business deleted successfully');
    }
}
