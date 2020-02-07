<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use App\StripeRawEntries;
use App\StripePayments;

class StripePaymentParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:stripepaymentparse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse raw payment entries into payments table';

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
        $charges = StripeRawEntries::where('processed', '=', 0 )->where('type', '=', 'charge.succeeded')->get();


        foreach ($charges as $charge){
            $payment = new StripePayments();
            $decoded = json_decode($charge->data, true);
            $payment->customer_id = $charge->customer_id;
            if ($payment->customer_id == null){
                $payment->customer_id = 'blank';
            }
            $payment->invoice_id = $decoded["data"]["object"]["invoice"];
            $payment->stripe_charge_id = $decoded["data"]["object"]["id"];
            $payment->amount = $decoded["data"]["object"]["amount"];
            $payment->date = $charge->created;
            $payment->api_version = $decoded["api_version"];
            $payment->save();

            $charge->processed = true;
            $charge->save();
        }

    }
}
