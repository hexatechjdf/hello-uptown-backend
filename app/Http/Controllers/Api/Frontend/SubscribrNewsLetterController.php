<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Website\SubscribeNewsletter\SubscribeNewsletterRequest;
use App\Http\Requests\Website\SubscribeNewsletter\UnsubscribeNewsletterRequest;
use App\Models\NewsletterSubscription;
use App\Resources\Website\NewsletterSubscriptionResource;
use App\Services\Website\NewsletterSubscriptionService;
use Illuminate\Http\Request;

class SubscribrNewsLetterController extends Controller
{
    public function __construct(protected NewsletterSubscriptionService $service) {}

    /**
     * Get list of newsletter subscriptions (Admin only)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $subscriptions = $this->service->list($request->all(), $perPage);

        return ApiResponse::collection(NewsletterSubscriptionResource::collection($subscriptions));
    }

    /**
     * Subscribe to newsletter (Public endpoint)
     */
    public function subscribe(SubscribeNewsletterRequest $request)
    {
        $subscription = $this->service->subscribe($request->email);
        $message = $subscription->wasRecentlyCreated? 'Thank you for subscribing to our newsletter!' : 'You are already subscribed to our newsletter.';
        return ApiResponse::resource(new NewsletterSubscriptionResource($subscription),$message);
    }


    public function unsubscribe($email)
    {
        if (!$email) {
            return view('unsubscribe.result', [
                'status' => 'error',
                'message' => 'Unable to unsubscribe. Email not found.'
            ]);
        }
        $unsubscribed = $this->service->unsubscribe($email);
        if ($unsubscribed) {
            return view('unsubscribe.result', ['status' => 'success', 'message' => 'You have been successfully unsubscribed from our newsletter.']);
        }

        return view('unsubscribe.result', ['status' => 'error','message' => 'Subscription not found or already unsubscribed.']);

    }


    // public function unsubscribe($email)
    // {
    //     if(!$email){
    //         return ApiResponse::error('Unable to unsubscribe. Email is not found', 404);
    //     }
    //     $unsubscribed = $this->service->unsubscribe($email);

    //     if ($unsubscribed) {
    //         return ApiResponse::success([], 'You have been unsubscribed from our newsletter.');
    //     }

    //     return ApiResponse::error('Unable to unsubscribe. Subscription not found or already unsubscribed.', 404);
    // }

    public function show(NewsletterSubscription $newsletterSubscription)
    {
        return ApiResponse::resource(new NewsletterSubscriptionResource($newsletterSubscription));
    }

    public function destroy(NewsletterSubscription $newsletterSubscription)
    {
        $this->service->delete($newsletterSubscription);

        return ApiResponse::success([], 'Subscription deleted successfully.');
    }

    /**
     * Get subscription statistics (Admin)
     */
    public function statistics()
    {
        $stats = $this->service->getStatistics();

        return ApiResponse::success($stats);
    }
}
