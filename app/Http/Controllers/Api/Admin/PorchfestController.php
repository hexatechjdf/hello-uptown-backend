<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PorchfestRequest;
use App\Http\Resources\PorchfestResource;
use App\Models\Porchfest;
use App\Repositories\PorchfestRepository;
use App\Services\PorchfestService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class PorchfestController extends Controller
{
    public function __construct(
        private PorchfestRepository $repo,
        private PorchfestService $service
    ) {}

    public function index(Request $request)
    {
        $list = $this->repo->list($request->all());
        return ApiResponse::collection(
            PorchfestResource::collection($list),
            'Porchfest list'
        );
    }

    public function store(PorchfestRequest $request)
    {
        $event = $this->service->store($request->validated());
        return ApiResponse::resource(new PorchfestResource($event), 'Created');
    }

    public function update(PorchfestRequest $request, Porchfest $porchfest)
    {
        $event = $this->service->update($porchfest, $request->validated());
        return ApiResponse::resource(new PorchfestResource($event), 'Updated');
    }

    public function destroy(Porchfest $porchfest)
    {
        $this->repo->delete($porchfest);
        return ApiResponse::success(null, 'Deleted');
    }
}

?>