<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;
    public $user;
    public $expirationHours;

    public function __construct($resetUrl, User $user)
    {
        $this->resetUrl = $resetUrl;
        $this->user = $user;
        $this->expirationHours = 24;
    }

    public function build()
    {
        return $this->subject('Password Reset Request')
                    ->view('emails.password-reset')
                    ->with([
                        'resetUrl' => $this->resetUrl,
                        'user' => $this->user,
                        'expirationHours' => $this->expirationHours
                    ]);
    }
}
