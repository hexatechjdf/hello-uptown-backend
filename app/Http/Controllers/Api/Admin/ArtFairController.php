<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Admin\ArtFair\ArtFairRequest;
use Illuminate\Http\Request;
use App\Models\ArtFair;
use App\Repositories\Admin\ArtFair\ArtFairRepository;
use App\Resources\Admin\ArtFair\ArtFairResource;
use App\Services\Admin\ArtFair\ArtFairService;

class ArtFairController extends Controller
{
    public function __construct(
        protected ArtFairRepository $repo,
        protected ArtFairService $service
    ) {}

    public function index(Request $request)
    {
        $data = $this->repo->list($request->all());
        return ApiResponse::collection(ArtFairResource::collection($data),'Art fairs list');
    }

    public function store(ArtFairRequest $request)
    {
        $data = $this->service->prepareData($request->validated());
        $artFair = $this->repo->create($data);
        return ApiResponse::resource(new ArtFairResource($artFair),'Art fair created');
    }

    public function show(ArtFair $artFair)
    {
        return ApiResponse::resource(new ArtFairResource($artFair),'Art fair details');
    }

    public function update(ArtFairRequest $request, ArtFair $artFair)
    {
        $data = $this->service->prepareData($request->validated());
        $artFair = $this->repo->update($artFair, $data);
        return ApiResponse::resource(new ArtFairResource($artFair),'Art fair updated');
    }

    public function destroy(ArtFair $artFair)
    {
        $this->repo->delete($artFair);
        return ApiResponse::success(null, 'Art fair deleted');
    }
}
