<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HealthWellness\HealthWellnessRequest;
use App\Resources\Admin\HealthWellness\HealthWellnessResource;
use App\Services\Admin\HealthWellness\HealthWellnessService;
use App\Models\HealthWellness;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class HealthWellnessController extends Controller
{
    protected $service;

    public function __construct(HealthWellnessService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $items = $this->service->all(
            $request->only(['search', 'status', 'category_id', 'featured']),
            $request->get('sort', 'created_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(HealthWellnessResource::collection($items), 'Health & Wellness list retrieved');
    }

    public function store(HealthWellnessRequest $request)
    {
        $item = $this->service->create($request->validated());
        return ApiResponse::resource(new HealthWellnessResource($item), 'Health & Wellness created successfully');
    }

    public function show($id)
    {
        $item = $this->service->find($id);
        return ApiResponse::resource(new HealthWellnessResource($item), 'Health & Wellness details retrieved');
    }

    public function update(HealthWellnessRequest $request, $id)
    {
        $item = $this->service->find($id);
        $item = $this->service->update($item, $request->validated());
        return ApiResponse::resource(new HealthWellnessResource($item), 'Health & Wellness updated successfully');
    }

    public function destroy($id)
    {
        $item = $this->service->find($id);
        $this->service->delete($item);
        return ApiResponse::success(null, 'Health & Wellness deleted successfully');
    }
}
