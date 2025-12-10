<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreDealRequest;
use App\Http\Requests\Deal\UpdateDealRequest;
use App\Http\Resources\DealResource;
use App\Models\Deal;
use App\Services\DealService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(protected DealService $service) {}

    public function index(Request $request)
    {
        $businessId = auth()->user()->business->id;

        $deals = $this->service->list($request->all(), $businessId);

        return ApiResponse::collection(DealResource::collection($deals));
    }

    public function store(StoreDealRequest $request)
    {
        $deal = $this->service->create($request->validated(), auth()->user()->business->id);

        return ApiResponse::resource(new DealResource($deal), 'Deal created successfully');
    }

    public function show(Deal $deal)
    {
        return ApiResponse::resource(new DealResource($deal));
    }

    public function update(UpdateDealRequest $request, Deal $deal)
    {
        $updated = $this->service->update($deal, $request->validated());

        return ApiResponse::resource(new DealResource($updated), 'Deal updated successfully');
    }

    public function destroy(Deal $deal)
    {
        $this->service->delete($deal);

        return ApiResponse::success([], 'Deal deleted successfully');
    }
}
