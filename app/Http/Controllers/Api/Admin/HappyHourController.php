<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HappyHourRequest;
use App\Http\Resources\HappyHourResource;
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
        $items = $this->service->repo->all(
            $request->only(['search','status']),
            $request->get('sort','date'),
            $request->get('order','desc'),
            $request->get('perPage',10)
        );

        return ApiResponse::collection(HappyHourResource::collection($items), 'Happy Hours list retrieved');
    }

    public function store(HappyHourRequest $request)
    {
        $item = $this->service->create($request->all());
        return ApiResponse::resource(new HappyHourResource($item), 'Happy Hours created successfully');
    }

    public function show($id)
    {
        $item = $this->service->repo->find($id);
        return ApiResponse::resource(new HappyHourResource($item));
    }

    public function update(HappyHourRequest $request, $id)
    {
        $item = $this->service->repo->find($id);
        $item = $this->service->update($item, $request->all());
        return ApiResponse::resource(new HappyHourResource($item), 'Happy Hours updated successfully');
    }

    public function destroy($id)
    {
        $item = $this->service->repo->find($id);
        $this->service->repo->delete($item);
        return ApiResponse::success(null, 'Happy Hours deleted successfully');
    }
}
