<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use App\StripeRawEntries;
use App\StripeInvoices;
use App\StripeInvoiceItems;

class StripeInvoiceParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:stripeinvoiceparse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse invoices into invoices and invoice items';

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
        $invoices = StripeRawEntries::where('processed', '=', 0 )->where('type', '=', 'invoice.payment_succeeded')
            ->get();

        foreach ($invoices as $invoice){
            $decoded = json_decode($invoice->data, true);
            $new_invoice = new StripeInvoices();
            $new_invoice->stripe_invoice_id = $decoded["data"]["object"]["id"];
            $new_invoice->stripe_charge_id = $decoded["data"]["object"]["charge"];
            $new_invoice->amount_due =$decoded["data"]["object"]["amount_due"];
            $new_invoice->date = $invoice->created;
            $new_invoice->api_version = $decoded["api_version"];
            $new_invoice->customer_id = $invoice->customer_id;
            $new_invoice->save();

            $data_structure = $decoded["data"]["object"]["lines"]["data"];

            foreach ($data_structure as $item) {
                $new_item = new StripeInvoiceItems();
                $new_item->invoice_id =  $new_invoice->id;
                $new_item->item_id = $item["id"];
                $new_item->amount = $item["amount"];
                $new_item->description = $item["description"];
                if ($item["plan"] !== null){
                    $new_item->stripe_plan_id = $item["plan"]["id"];
                    $new_item->plan_name = $item["plan"]["name"];
                    $new_item->description = $item["plan"]["name"];
                }
                $new_item->save();
            }
            $invoice->processed = true;
            $invoice->save();

        }

    }
}
