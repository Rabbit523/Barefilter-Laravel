<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderSubscriptionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $order)
    {
        $this->admin = $admin;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Bare Filter | Et filterabonnement er  behandlet")
            ->view('emails.order-subscription-notification');
    }
}
