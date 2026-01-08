<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deal;
use App\Models\NewsletterSubscription;
use App\Mail\NewDealMail;
use Illuminate\Support\Facades\Mail;

class SendDealEmailsCommand extends Command
{
    protected $signature = 'deal:send-emails {deal_id}';
    protected $description = 'Send deal email notifications to subscribers';

    public function handle(): void
    {
        $deal = Deal::findOrFail($this->argument('deal_id'));

        $emails = NewsletterSubscription::where('status', 'active')->whereNull('unsubscribed_at')->pluck('email');

        if ($emails->isEmpty()) {
            return;
        }

        $emails->chunk(15)->each(function ($batch) use ($deal) {

            foreach ($batch as $email) {
                try {
                    Mail::to($email)->send(new NewDealMail($deal));
                } catch (\Exception $e) {
                    // ignore single failures
                }
            }
            sleep(60);
        });
    }
}
