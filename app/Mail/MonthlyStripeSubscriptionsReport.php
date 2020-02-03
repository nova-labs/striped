<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MonthlyStripeSubscriptionsReport extends Mailable
{
    use Queueable, SerializesModels;

    public $plan_counts;
    public $span;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($plan_counts, $span)
    {
        $this->plan_counts = $plan_counts;
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
            ->markdown('emails.monthlyStripeSubscriptions');
    }
}
