<?php
namespace App\Http\Controllers\Api\V1\Business;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
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

        return ApiResponse::paginated(
            RedemptionResource::collection($result),
            'Redemption list retrieved successfully',
            $result
        );
    }
}
