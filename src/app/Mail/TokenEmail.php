<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TokenEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $onetime_token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $onetime_token)
    {
        $this->email = $email;
        $this->onetime_token = $onetime_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
                    ->subject('メール認証')
                    ->view('auth.token-email')
                    ->with([
                        'email' => $this->email,
                        'onetime_token' => $this->onetime_token
                    ]);
    }
}
