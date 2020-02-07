<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StripeReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:stripereset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset invoices, invoice_items and payments';

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
        DB::table('stripe_invoice_items')->delete();
        DB::table('stripe_invoices')->delete();
        DB::table('stripe_payments')->delete();

        DB::table('stripe_raw_entries')
            ->where('type', '=', 'charge.refunded')
            ->orWhere('type', '=', 'invoice.payment_succeeded')
            ->orWhere('type', '=', 'charge.succeeded')
            ->update(['processed' => 0]);
    }
}
