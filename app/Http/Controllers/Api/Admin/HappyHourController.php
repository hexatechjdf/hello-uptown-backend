<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HappyHour\HappyHourRequest;
use App\Resources\Admin\HappyHour\HappyHourResource;
use App\Services\Admin\HappyHour\HappyHourService;
use App\Models\HappyHour;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class HappyHourController extends Controller
{
    protected $service;

    public function __construct(HappyHourService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $items = $this->service->all(
            $request->only(['search', 'status', 'category_id', 'featured']),
            $request->get('sort', 'created_at'), // Changed from 'date' to 'created_at'
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(HappyHourResource::collection($items), 'Happy Hours list retrieved');
    }

    public function store(HappyHourRequest $request)
    {
        $item = $this->service->create($request->validated()); // Changed from all() to validated()
        return ApiResponse::resource(new HappyHourResource($item), 'Happy Hours created successfully');
    }

    public function show($id)
    {
        $item = $this->service->find($id);
        return ApiResponse::resource(new HappyHourResource($item), 'Happy Hours details retrieved');
    }

    public function update(HappyHourRequest $request, $id)
    {
        $item = $this->service->find($id);
        $item = $this->service->update($item, $request->validated()); // Changed from all() to validated()
        return ApiResponse::resource(new HappyHourResource($item), 'Happy Hours updated successfully');
    }

    public function destroy($id)
    {
        $item = $this->service->find($id);
        $this->service->delete($item);
        return ApiResponse::success(null, 'Happy Hours deleted successfully');
    }
}
