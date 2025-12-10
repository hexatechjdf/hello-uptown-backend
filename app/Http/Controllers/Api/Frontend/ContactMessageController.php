<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ContactMessage\ContactMessageService;
use App\Helpers\ApiResponse;

class ContactMessageController extends Controller
{
    protected $service;

    public function __construct(ContactMessageService $service)
    {
        $this->service = $service;
    }

    public function submit(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        $this->service->submit($request->only([
            'full_name',
            'email',
            'phone',
            'message',
        ]));

        return ApiResponse::success([], 'Message submitted successfully');
    }
}
