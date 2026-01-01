<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dining\DiningRequest;
use App\Resources\Admin\Dining\DiningResource;
use App\Services\Admin\Dining\DiningService;
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
        $dinings = $this->service->getAll(
            $request->only(['search', 'status']),
            $request->get('sort', 'created_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
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
        $dining = $this->service->find($id);
        return ApiResponse::resource(new DiningResource($dining));
    }

    public function update(DiningRequest $request, $id)
    {
        $dining = $this->service->find($id);
        $dining = $this->service->update($dining, $request->all());
        return ApiResponse::resource(new DiningResource($dining), 'Dining updated successfully');
    }

     public function destroy($id)
    {
        $dining = $this->service->find($id);
        $dining->delete();
        return ApiResponse::success(null, 'Dining deleted successfully');
    }
}
