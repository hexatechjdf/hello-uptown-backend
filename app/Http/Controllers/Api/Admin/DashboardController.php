<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Services\Admin\AdminDashboardService;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(AdminDashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->stats();

        return ApiResponse::success($data, 'Admin dashboard stats');
    }
}
