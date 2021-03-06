<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WeeklyStripeReport extends Mailable
{
    use Queueable, SerializesModels;

    public $churn;
    public $span;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($churn, $span)
    {
        $this->churn = $churn;
        $this->span = $span;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@nova-labs.org')
            ->markdown('emails.stripeWeeklyChurn');
    }
}
