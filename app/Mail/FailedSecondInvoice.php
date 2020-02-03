<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FailedSecondInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $failed;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $failed)
    {
        $this->name = $name;
        $this->failed = $failed;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('membership@nova-labs.org')
            ->replyTo('membership@nova-labs.org', 'Nova Labs Membership Team')
            ->subject('Member: ' . $this->name . ' - payment has failed second time')
            ->markdown('emails.failedSecondInvoice');
    }
}
