<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MonthlyStripePaymentsReport extends Mailable
{
    use Queueable, SerializesModels;

    public $charges;
    public $span;
    public $total;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($total, $charges, $span)
    {
        $this->charges = $charges;
        $this->span = $span;
        $this->total = $total;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@nova-labs.org')
            ->markdown('emails.monthlyStripePayments');
    }
}
