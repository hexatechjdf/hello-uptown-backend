<?php

namespace App\Http\Controllers\Api\Redemption;

use App\Http\Controllers\Controller;
use App\Http\Requests\Redemption\ListRedemptionRequest;
use App\Services\Redemption\RedemptionService;
use App\Helpers\ApiResponse;
use App\Resources\Redemption\RedemptionResource;
use App\Models\Redemption;

class RedemptionController extends Controller
{
    protected $service;

    public function __construct(RedemptionService $service)
    {
        $this->service = $service;
    }

    public function index(ListRedemptionRequest $request)
    {
        $result = $this->service->list($request->validated());
        return ApiResponse::collection(RedemptionResource::collection($result),'Redemption list retrieved successfully');
    }
    public function RedemptionStats()
    {
        $stats = Redemption::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $response = [
            'total'     => $stats->sum(),
            'completed' => $stats['completed'] ?? 0,
            'pending'   => $stats['pending'] ?? 0,
            'disputed'  => $stats['disputed'] ?? 0,
            'verified'  => $stats['verified'] ?? 0,
        ];
        return ApiResponse::success($response, 'Redemption statistics fetched successfully');
    }
}
