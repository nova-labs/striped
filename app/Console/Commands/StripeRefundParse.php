<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use App\StripeRawEntries;
use App\StripePayments;

class StripeRefundParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:striperefundparse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse raw refund entries into payments table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $charges = StripeRawEntries::where('processed', '=', 0 )->where('type', '=', 'charge.refunded')->get();


        foreach ($charges as $charge){
            $payment = new StripePayments();
            $decoded = json_decode($charge->data, true);
            $payment->customer_id = $charge->customer_id;
            $payment->invoice_id = $decoded["data"]["object"]["invoice"];
            $payment->stripe_charge_id = $decoded["data"]["object"]["id"];
            $payment->amount = "-" . $decoded["data"]["object"]["amount_refunded"];
            $payment->date = $charge->created;
            $payment->api_version = $decoded["api_version"];
            $payment->save();

            $charge->processed = true;
            $charge->save();
        }

    }
}
