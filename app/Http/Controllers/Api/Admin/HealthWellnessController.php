<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthWellnessRequest;
use App\Http\Resources\HealthWellnessResource;
use App\Services\HealthWellness\HealthWellnessService;
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
        $items = $this->service->repo->all(
            $request->only(['search','status']),
            $request->get('sort','date'),
            $request->get('order','desc'),
            $request->get('perPage',10)
        );

        return ApiResponse::collection(HealthWellnessResource::collection($items), 'Health & Wellness list retrieved');
    }

    public function store(HealthWellnessRequest $request)
    {
        $item = $this->service->create($request->all());
        return ApiResponse::resource(new HealthWellnessResource($item), 'Health & Wellness created successfully');
    }

    public function show($id)
    {
        $item = $this->service->repo->find($id);
        return ApiResponse::resource(new HealthWellnessResource($item));
    }

    public function update(HealthWellnessRequest $request, $id)
    {
        $item = $this->service->repo->find($id);
        $item = $this->service->update($item, $request->all());
        return ApiResponse::resource(new HealthWellnessResource($item), 'Health & Wellness updated successfully');
    }

    public function destroy($id)
    {
        $item = $this->service->repo->find($id);
        $this->service->repo->delete($item);
        return ApiResponse::success(null, 'Health & Wellness deleted successfully');
    }
}
