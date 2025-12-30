<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\SettingRequest;
use App\Resources\Admin\Setting\SettingResource;
use App\Services\Admin\Setting\SettingService;
use App\Helpers\ApiResponse;

class SettingController extends Controller
{
    protected $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function store(SettingRequest $request)
    {
        $setting = $this->service->save(
            $request->key,
            $request->value
        );

        return ApiResponse::resource(
            new SettingResource($setting),
            'Setting saved successfully'
        );
    }

    public function show(string $key)
    {
        $setting = $this->service->getByKey($key);

        if (!$setting) {
            return ApiResponse::error('No data found', 404);
        }

        return ApiResponse::resource(
            new SettingResource($setting),
            'Setting retrieved successfully'
        );
    }

    public function destroy(string $key)
    {
        $deleted = $this->service->delete($key);

        if (!$deleted) {
            return ApiResponse::error('No data found', 404);
        }

        return ApiResponse::success(null, 'Setting deleted successfully');
    }
}
