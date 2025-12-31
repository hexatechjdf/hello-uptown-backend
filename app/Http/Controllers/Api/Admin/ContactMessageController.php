<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ContactMessage\ContactMessageService;
use App\Resources\Admin\ContactMessage\ContactMessageResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    protected $service;

    public function __construct(ContactMessageService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $messages = $this->service->getAll(
            $request->only(['search']),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            ContactMessageResource::collection($messages),
            'Contact messages retrieved'
        );
    }

    public function show($id)
    {
        $message = $this->service->find($id);

        return ApiResponse::resource(
            new ContactMessageResource($message),
            'Contact message retrieved'
        );
    }

    public function destroy($id)
    {
        $message = $this->service->find($id);
        $this->service->delete($message);

        return ApiResponse::success(null, 'Contact message deleted successfully');
    }
}
