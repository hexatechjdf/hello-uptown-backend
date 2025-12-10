<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BusinessService;
use App\Http\Resources\Admin\AdminBusinessResource;
use App\Helpers\ApiResponse;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function __construct(
        protected BusinessService $service
    ) {}

    public function index(Request $request)
    {
        $businesses = $this->service->list($request->all());

        return ApiResponse::collection(
            AdminBusinessResource::collection($businesses),
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
            new AdminBusinessResource($business),
            'Business created successfully',
            [],
            201
        );
    }

    public function show(Business $business)
    {
        return ApiResponse::resource(
            new AdminBusinessResource($business->load('user')),
            'Business details'
        );
    }

    public function update(Request $request, Business $business)
    {
        $data = $request->validate([
            'business_name' => 'sometimes|string|max:255',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string',
            'category'      => 'nullable|string',
            'status'        => 'boolean',
        ]);

        $updated = $this->service->update($business, $data);

        return ApiResponse::resource(
            new AdminBusinessResource($updated),
            'Business updated successfully'
        );
    }

    public function destroy(Business $business)
    {
        $business->delete();

        return ApiResponse::success(null, 'Business deleted successfully');
    }
}
