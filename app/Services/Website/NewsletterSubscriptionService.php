<?php

namespace App\Services\Website;

use App\Models\NewsletterSubscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NewsletterSubscriptionService
{
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = NewsletterSubscription::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('email', 'like', "%{$search}%");
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $query->whereDate('subscribed_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('subscribed_at', '<=', $filters['end_date']);
        }

        // Apply sorting
        $sortField = $filters['sort_by'] ?? 'subscribed_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        return $query->paginate($perPage);
    }

    public function subscribe(string $email): NewsletterSubscription
    {
        return DB::transaction(function () use ($email) {
            // Check if email already exists
            $existingSubscription = NewsletterSubscription::byEmail($email)->first();

            if ($existingSubscription) {
                // If unsubscribed, resubscribe
                if ($existingSubscription->isUnsubscribed()) {
                    $existingSubscription->resubscribe();
                    return $existingSubscription;
                }

                // If already active, return existing
                return $existingSubscription;
            }

            // Create new subscription
            return NewsletterSubscription::create([
                'email' => $email,
                'status' => 'active',
            ]);
        });
    }

    public function unsubscribe(string $email): bool
    {
        $subscription = NewsletterSubscription::byEmail($email)->first();

        if ($subscription && $subscription->isActive()) {
            $subscription->unsubscribe();
            return true;
        }

        return false;
    }

    public function delete(NewsletterSubscription $subscription): bool
    {
        return DB::transaction(function () use ($subscription) {
            return $subscription->delete();
        });
    }

    public function getStatistics(): array
    {
        return [
            'total' => NewsletterSubscription::count(),
            'active' => NewsletterSubscription::active()->count(),
            'unsubscribed' => NewsletterSubscription::where('status', 'unsubscribed')->count(),
            'bounced' => NewsletterSubscription::where('status', 'bounced')->count(),
            'today_subscriptions' => NewsletterSubscription::whereDate('subscribed_at', today())->count(),
        ];
    }
}
