<?php

namespace App\Observers;

use App\Models\Deal;
use App\Models\NewsletterSubscription;
use App\Mail\NewDealMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;

class DealObserver
{

    public function created(Deal $deal): void
    {
        if ($deal->status != 1) {
            return;
        }
        $command = [
            'php',
            base_path('artisan'),
            'deal:send-emails',
            $deal->id
        ];
        // run in background
        $process = new Process($command);
        $process->setTimeout(null);
        $process->start(); // async, returns immediately
    }
}
