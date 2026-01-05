<?php

namespace App\Http\Controllers\Api\Deal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreDealRequest;
use App\Http\Requests\Deal\UpdateDealRequest;
use App\Resources\Deal\DealResource;
use App\Models\Deal;
use App\Services\Deal\DealService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(protected DealService $service) {}

    public function index(Request $request)
    {
                $businessId = request()->query('business_id');

        $businessId = $businessId !== null && $businessId != 0
        ? (int) $businessId
        : auth()->user()->business->id;

        $deals = $this->service->list($request->all(), $businessId);

        return ApiResponse::collection(DealResource::collection($deals));
    }

    public function store(StoreDealRequest $request)
    {
                $businessId = request()->query('business_id');

        $businessId = $businessId !== null && $businessId != 0
        ? (int) $businessId
        : auth()->user()->business->id;
        $deal = $this->service->create($request->validated(), $businessId);

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

    public function dealStats()
    {
                $businessId = request()->query('business_id');

        $businessId = $businessId !== null && $businessId != 0
        ? (int) $businessId
        : auth()->user()->business->id;

        $stats = Deal::where('business_id', $businessId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalDeals = $stats->sum();

        $response = [
            'total' => $totalDeals,
            'stats' => [
                [
                    'status' => "all",
                    'label'  => 'Total',
                    'count'  => $totalDeals,
                ],
                [
                    'status' => 1,
                    'label'  => 'Active',
                    'count'  => $stats[1] ?? 0,
                ],
                [
                    'status' => 2,
                    'label'  => 'Scheduled',
                    'count'  => $stats[2] ?? 0,
                ],
                [
                    'status' => 3,
                    'label'  => 'Expired',
                    'count'  => $stats[3] ?? 0,
                ],
                [
                    'status' => 4,
                    'label'  => 'Draft',
                    'count'  => $stats[4] ?? 0,
                ],
            ],
        ];

        return ApiResponse::success($response, 'Deal statistics fetched successfully');
    }

}
