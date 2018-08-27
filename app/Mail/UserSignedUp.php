<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSignedUp extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $admin;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $user)
    {
        $this->admin = $admin;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Bare Filter | En ny bruker har opprettet en konto")
            ->view('emails.user-signup');
    }
}
