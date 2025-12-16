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

        return ApiResponse::resource(
            new BusinessResource($business->load('user')),
            'Business profile fetched successfully'
        );
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
            'user_id'        => 'required|exists:users,id',
            'business_name'  => 'required|string|max:255',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string',
            'category'       => 'nullable|string',
            'status'         => 'boolean',
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
            'email'         => 'sometimes|email',
            'phone'         => 'sometimes|string',
            'category_id'       => 'sometimes|exists:categories,id',
            'tags'          => 'sometimes|array',
            'tags.*'        => 'string',
            'short_description' => 'sometimes|string',
            'long_description' => 'sometimes|string',
            'opening_hours' => 'sometimes|string',
            'status'        => 'boolean',
            'address'    => 'sometimes|string',
            'latitude'   => 'sometimes|numeric',
            'longitude'  => 'sometimes|numeric',
            'website'   =>  'sometimes|url',
            'facebook_link' => 'sometimes|url',
            'instagram_link' => 'sometimes|url',
            'twitter_link' => 'sometimes|url',
            'redemption_radius' => 'sometimes|integer',
        ]);

        $updated = $this->service->update($business, $data);
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
