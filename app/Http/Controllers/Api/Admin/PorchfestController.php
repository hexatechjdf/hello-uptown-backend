<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Porchfest;
use App\Helpers\ApiResponse;
use App\Http\Requests\PorchfestRequest;
use App\Repositories\Admin\Porchfest\PorchfestRepository;
use App\Resources\Admin\Porchfest\PorchfestResource;
use App\Services\Admin\Porchfest\PorchfestService;
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
    public function show(Porchfest $porchfest)
    {
        return ApiResponse::resource(
            new PorchfestResource($porchfest),
            'Porchfest details retrieved'
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
