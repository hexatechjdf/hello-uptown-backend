<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Helpers\ApiResponse;
use App\Http\Requests\Website\ContactMessage\StoreContactMessageRequest;
use App\Http\Requests\Website\ContactMessage\UpdateContactMessageRequest;
use App\Resources\Website\ContactMessageResource;
use App\Services\Website\ContactMessageService;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
        public function __construct(protected ContactMessageService $service) {}
        public function index(Request $request)
        {
            $perPage = $request->get('per_page', 15);
            $contactMessages = $this->service->list($request->all(), $perPage);
            return ApiResponse::collection(ContactMessageResource::collection($contactMessages));
        }

        public function store(StoreContactMessageRequest $request)
        {
            $contactMessage = $this->service->create($request->validated());
            return ApiResponse::resource(new ContactMessageResource($contactMessage),'Thank you for contacting us. We will get back to you soon.');
        }
        public function show(ContactMessage $contactMessage)
        {
            // Optional: Mark as read when viewing
            // $this->service->markAsRead($contactMessage);
            return ApiResponse::resource(new ContactMessageResource($contactMessage));
        }

        public function update(UpdateContactMessageRequest $request, ContactMessage $contactMessage)
        {
            $updatedContactMessage = $this->service->update($contactMessage, $request->validated());

            return ApiResponse::resource(
                new ContactMessageResource($updatedContactMessage),
                'Contact message updated successfully'
            );
        }

        public function destroy(ContactMessage $contactMessage)
        {
            $this->service->delete($contactMessage);

            return ApiResponse::success([], 'Contact message deleted successfully');
        }

        public function submitContactForm(StoreContactMessageRequest $request)
        {
            $contactMessage = $this->service->create($request->validated());

            // Optional: Send email notification here
            // $this->sendNotificationEmail($contactMessage);

            return ApiResponse::resource(new ContactMessageResource($contactMessage),'Thank you for your message. We will contact you soon.');
        }

        public function bulkDelete(Request $request)
        {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:contact_messages,id',
            ]);

            ContactMessage::whereIn('id', $request->ids)->delete();

            return ApiResponse::success([], 'Selected contact messages deleted successfully');
        }

        public function statistics(Request $request)
        {
            $total = ContactMessage::count();
            $today = ContactMessage::whereDate('created_at', today())->count();
            $last7Days = ContactMessage::where('created_at', '>=', now()->subDays(7))->count();

            return ApiResponse::success([
                'total_messages' => $total,
                'today_messages' => $today,
                'last_7_days' => $last7Days,
            ]);
        }
}
