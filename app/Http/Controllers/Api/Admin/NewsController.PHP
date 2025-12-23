<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\NewsRequest;
use App\Resources\Admin\News\NewsResource;
use App\Services\Admin\News\NewsService;
use App\Models\News;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $service;

    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $items = $this->service->all(
            $request->only(['search','status']),
            $request->get('sort','date'),
            $request->get('order','desc'),
            $request->get('perPage',10)
        );

        return ApiResponse::collection(NewsResource::collection($items), 'News list retrieved');
    }

    public function store(NewsRequest $request)
    {
        $item = $this->service->create($request->all());
        return ApiResponse::resource(new NewsResource($item), 'News created successfully');
    }

    public function show($id)
    {
        $item = $this->service->find($id);
        return ApiResponse::resource(new NewsResource($item));
    }

    public function update(NewsRequest $request, $id)
    {
        $item = $this->service->find($id);
        $item = $this->service->update($item, $request->all());
        return ApiResponse::resource(new NewsResource($item), 'News updated successfully');
    }

    public function destroy($id)
    {
        $item = $this->service->find($id);
        $this->service->delete($item);
        return ApiResponse::success(null, 'News deleted successfully');
    }
}
