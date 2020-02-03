<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuccessCharge extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $event)
    {
        $this->name = $name;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@nova-labs.org')
            ->markdown('emails.successCharge');
    }
}
