<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FailedCharge extends Mailable
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
        return $this->from('no-reply@nova-labs.org')
            ->replyTo('membership@nova-labs.org', 'Nova Labs Membership Team')
            ->subject('Problem with your credit card at Nova Labs')
            ->markdown('emails.failedCharge');
    }
}
