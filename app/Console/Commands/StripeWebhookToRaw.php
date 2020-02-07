<?php

namespace App\Console\Commands;

use App\WebhookCalls;
use Illuminate\Console\Command;

use App\StripeRawEntries;

class StripeWebhookToRaw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:webhookToRaw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move webhook items into raw for further processing';

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
        $webhooks = WebhookCalls::where('processed', '=', false)
            ->get();

        foreach($webhooks as $webhook){
            $decoded = json_decode($webhook->payload, true);
            $event_id = $decoded["id"];

            $status = StripeRawEntries::where('stripe_event_id','=', $event_id)->first();

            if(!isset($status)) {

                $new_entry = new StripeRawEntries();
                $new_entry->stripe_event_id = $event_id;
                $new_entry->data = $webhook->payload;
                $new_entry->name = $webhook->name;
                $new_entry->api_version = $decoded["api_version"];
                $new_entry->created = date("Y-m-d H:i:s", $decoded["created"]);
                if (isset($decoded["data"]["object"]["customer"])) {
                    $new_entry->customer_id = $decoded["data"]["object"]["customer"];
                }
                if ($new_entry->type == 'customer.updated' or $new_entry->type == 'customer.created') {
                    $new_entry->customer_id = $decoded["data"]["object"]["id"];
                }
                $new_entry->valid = true;
                $new_entry->save();
            }
            $webhook->processed = true;
            $webhook->save();

        }

    }
}
