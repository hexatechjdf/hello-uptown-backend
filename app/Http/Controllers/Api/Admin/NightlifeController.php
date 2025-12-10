<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NightlifeRequest;
use App\Http\Resources\NightlifeResource;
use App\Services\Admin\Nightlife\NightlifeService;
use App\Models\Nightlife;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class NightlifeController extends Controller
{
    protected $service;

    public function __construct(NightlifeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $nightlifes = $this->service->repo->all(
            $request->only(['search','status']),
            $request->get('sort','date'),
            $request->get('order','desc'),
            $request->get('perPage',10)
        );

        return ApiResponse::collection(NightlifeResource::collection($nightlifes), 'Nightlife list retrieved');
    }

    public function store(NightlifeRequest $request)
    {
        $nightlife = $this->service->create($request->all());
        return ApiResponse::resource(new NightlifeResource($nightlife), 'Nightlife created successfully');
    }

    public function show($id)
    {
        $nightlife = $this->service->repo->find($id);
        return ApiResponse::resource(new NightlifeResource($nightlife));
    }

    public function update(NightlifeRequest $request, $id)
    {
        $nightlife = $this->service->repo->find($id);
        $nightlife = $this->service->update($nightlife, $request->all());
        return ApiResponse::resource(new NightlifeResource($nightlife), 'Nightlife updated successfully');
    }

    public function destroy($id)
    {
        $nightlife = $this->service->repo->find($id);
        $this->service->repo->delete($nightlife);
        return ApiResponse::success(null, 'Nightlife deleted successfully');
    }
}
