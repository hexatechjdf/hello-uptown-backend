<?php

namespace App\Http\Controllers\Api\Redemption;

use App\Http\Controllers\Controller;
use App\Http\Requests\Redemption\ListRedemptionRequest;
use App\Services\Redemption\RedemptionService;
use App\Helpers\ApiResponse;
use App\Resources\Redemption\RedemptionResource;

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
        return ApiResponse::collection(
            RedemptionResource::collection($result),
            'Redemption list retrieved successfully'
        );
    }
}
