<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StripeRawEntries;
use App\StripeSubscriptions;

class StripeSubscriptionParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:stripeSubscriptionParse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse subscription changes into ';

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
        $subscriptions = StripeRawEntries::where('processed', '=', 0 )->whereIn('type',
            [   'customer.subscription.deleted',
                'customer.subscription.created',
                'customer.subscription.updated',
                ])
            ->get();

        foreach ($subscriptions as $subscription){
            $decoded = json_decode($subscription->data, true);
            $new_subscription = new StripeSubscriptions();
            $new_subscription->type = $subscription->type;

            if ($new_subscription->type  == 'customer.subscription.created'){
                $new_subscription->stripe_subscription_id = $decoded["data"]["object"]["id"];
                $new_subscription->stripe_plan_id = $decoded["data"]["object"]["plan"]["id"];
            }
            elseif($new_subscription->type  == 'customer.subscription.deleted'){
                $new_subscription->stripe_subscription_id = $decoded["data"]["object"]["id"];
                $new_subscription->stripe_plan_id = $decoded["data"]["object"]["plan"]["id"];
                // cancel date should be captured
            }
            else{
                $new_subscription->stripe_subscription_id = $decoded["data"]["object"]["id"];
                if (isset($decoded["data"]["object"]["plan"])){
                    $new_subscription->stripe_plan_id = $decoded["data"]["object"]["plan"]["id"];
                }
                else{
                    $new_subscription->stripe_plan_id = $decoded["data"]["object"]["items"]["data"]["plan"]["id"];
                }

                if(isset($decoded["data"]["previous_attributes"])){
                    if(isset($decoded["data"]["previous_attributes"]["items"])){
                        $new_subscription->stripe_plan_id_previous = $decoded["data"]["previous_attributes"]["items"]["data"]["0"]["plan"]["id"];
                    }
                    else{
                        if(isset($decoded["data"]["previous_attributes"]["plan"]))
                            $new_subscription->stripe_plan_id_previous = $decoded["data"]["previous_attributes"]["plan"]["id"];
                    }
                }

            }

            $new_subscription->event_date = date("Y-m-d H:i:s", $decoded["created"]);
            $new_subscription->date_start = date("Y-m-d H:i:s", $decoded["data"]["object"]["current_period_start"]);
            $new_subscription->date_end = date("Y-m-d H:i:s", $decoded["data"]["object"]["current_period_end"]);
            $new_subscription->api_version = $decoded["api_version"];
            $new_subscription->customer_id = $subscription->customer_id;


            $new_subscription->save();
            //echo ($subscription->id . "\n");


            $subscription->processed = true;
            $subscription->save();

        }

        dd(count($subscriptions));
    }
}
