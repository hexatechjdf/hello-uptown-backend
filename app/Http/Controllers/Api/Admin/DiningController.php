<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiningRequest;
use App\Http\Resources\DiningResource;
use App\Services\Dining\DiningService;
use App\Models\Dining;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class DiningController extends Controller
{
    protected $service;

    public function __construct(DiningService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $dinings = $this->service->repo->all(
            $request->only(['search','status']),
            $request->get('sort','date'),
            $request->get('order','desc'),
            $request->get('perPage',10)
        );

        return ApiResponse::collection(DiningResource::collection($dinings), 'Dining list retrieved');
    }

    public function store(DiningRequest $request)
    {
        $dining = $this->service->create($request->all());
        return ApiResponse::resource(new DiningResource($dining), 'Dining created successfully');
    }

    public function show($id)
    {
        $dining = $this->service->repo->find($id);
        return ApiResponse::resource(new DiningResource($dining));
    }

    public function update(DiningRequest $request, $id)
    {
        $dining = $this->service->repo->find($id);
        $dining = $this->service->update($dining, $request->all());
        return ApiResponse::resource(new DiningResource($dining), 'Dining updated successfully');
    }

    public function destroy($id)
    {
        $dining = $this->service->repo->find($id);
        $this->service->repo->delete($dining);
        return ApiResponse::success(null, 'Dining deleted successfully');
    }
}
