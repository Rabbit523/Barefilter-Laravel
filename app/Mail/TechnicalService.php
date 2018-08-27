<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TechnicalService extends Mailable
{
    use Queueable, SerializesModels;

    public $properties;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Bare Filter | Borettslag & Sameie")
            ->view('emails.technical-service');
    }
}
