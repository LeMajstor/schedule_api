<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailValidation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $token;
    public $confirmationLink;

    public function __construct($token, $confirmationLink)
    {
        $this->token = $token;
        $this->confirmationLink = $confirmationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.validation', [
            'token' => $this->token,
            'confirmation_link' => $this->confirmationLink
        ]);
    }
}
