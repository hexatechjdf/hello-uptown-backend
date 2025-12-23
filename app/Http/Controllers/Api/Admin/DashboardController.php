<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }
D:\laragon\www\HTS-Projects\hello-uptown-backend\app\Http\Controllers\Api\Frontend\AllPages
    public function index()
    {
        $data = $this->service->stats();

        return ApiResponse::success($data, 'Admin dashboard stats');
    }
}
