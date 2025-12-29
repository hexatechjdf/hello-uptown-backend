<?php

namespace App\Mail;

use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDealMail extends Mailable
{
    use Queueable, SerializesModels;

    public Deal $deal;

    public function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }

    public function build()
    {
        return $this->subject('🔥 New Deal Just Dropped!')
            ->view('emails.new-deal');
    }
}
