<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $service)
    {
        $this->service = $service;
    }

    public function redemptionsTrend(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:daily,weekly,monthly,yearly',
            'type' => 'nullable|in:deal,coupon,all',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $businessId = $request->query('business_id');
        $businessId = $businessId !== null && $businessId != 0
            ? (int) $businessId
            : auth()->user()->business->id;

        $period = $request->input('period', 'weekly');
        $type = $request->input('type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->service->getRedemptionsTrend($businessId, $period, $type, $startDate, $endDate);

        return ApiResponse::success($data, 'Redemptions trend data fetched successfully');
    }

    public function categoryDistribution(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:today,weekly,monthly,yearly,custom',
            'type' => 'nullable|in:deal,coupon,all',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $businessId = $request->query('business_id');
        $businessId = $businessId !== null && $businessId != 0
            ? (int) $businessId
            : auth()->user()->business->id;

        $period = $request->input('period', 'weekly');
        $type = $request->input('type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->service->getCategoryDistribution($businessId, $period, $type, $startDate, $endDate);

        return ApiResponse::success($data, 'Category distribution data fetched successfully');
    }
}
