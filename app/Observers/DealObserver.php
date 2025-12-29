<?php

namespace App\Observers;

use App\Models\Deal;
use App\Models\NewsletterSubscription;
use App\Mail\NewDealMail;
use Illuminate\Support\Facades\Mail;

class DealObserver
{

    public function created(Deal $deal): void
    {
        $emails = NewsletterSubscription::where('status', 'active')->whereNull('unsubscribed_at')->pluck('email')->toArray();

        if (empty($emails)) {
            return;
        }
        Mail::to($emails)->send(new NewDealMail($deal));
    }
}
