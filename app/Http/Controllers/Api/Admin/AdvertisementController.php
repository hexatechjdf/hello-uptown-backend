<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Advertisement\AdvertisementRequest;
use App\Resources\Admin\Advertisement\AdvertisementResource;
use App\Services\Admin\Advertisement\AdvertisementService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    protected $service;

    public function __construct(AdvertisementService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $ads = $this->service->getAll(
            $request->only(['search', 'status']),
            $request->get('sort', 'created_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            AdvertisementResource::collection($ads),
            'Advertisement list retrieved'
        );
    }

    public function store(AdvertisementRequest $request)
    {
        $ad = $this->service->create($request->all());

        return ApiResponse::resource(
            new AdvertisementResource($ad),
            'Advertisement created successfully'
        );
    }

    public function show($id)
    {
        $ad = $this->service->find($id);
        return ApiResponse::resource(new AdvertisementResource($ad));
    }

    public function update(AdvertisementRequest $request, $id)
    {
        $ad = $this->service->find($id);
        $ad = $this->service->update($ad, $request->all());

        return ApiResponse::resource(
            new AdvertisementResource($ad),
            'Advertisement updated successfully'
        );
    }

    public function destroy($id)
    {
        $ad = $this->service->find($id);
        $ad->delete();

        return ApiResponse::success(null, 'Advertisement deleted successfully');
    }
}
