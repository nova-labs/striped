<?php
/**
 * Created by PhpStorm.
 * User: johnhoskins
 * Date: 1/26/19
 * Time: 9:56 PM
 */



namespace App\Jobs\StripeWebhooks;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\StripeWebhooks\StripeWebhookCall;

use Illuminate\Support\Facades\Mail;
use App\Mail\FailedCharge;
use App\User;

class HandleFailedCharge implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\StripeWebhooks\StripeWebhookCall */
    public $webhookCall;

    public function __construct(StripeWebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        // do your work here

        // you can access the payload of the webhook call with `$this->webhookCall->payload`

        $info = $this->webhookCall->payload;

        $failed = [];
        $failed['stripe_customer_id'] = $info['data']['object']['customer'];
        $failed['stripe_customer_email'] = $info['data']['object']['source']['name'];
        $failed['failure_code'] = $info['data']['object']['failure_code'];
        $failed['failure_message'] = $info['data']['object']['failure_message'];
        $failed['seller_message'] = $info['data']['object']['outcome']['seller_message'];
        $failed['time'] = Carbon::createFromTimestamp($info['data']['object']['created'])
            ->timezone('America/New_York')->toDayDateTimeString();
        $failed['card_details'] = "Card: " .  $info['data']['object']['source']['brand'] .
            " ending with " . $info['data']['object']['source']['last4'] .
            " expiring " . $info['data']['object']['source']['exp_month'] .
            "/" . $info['data']['object']['source']['exp_year'] ;

        $failed['card_brand'] = $info['data']['object']['source']['brand'];
        $failed['card_expiration'] = $info['data']['object']['source']['exp_month'] .
            "/" . $info['data']['object']['source']['exp_year'] ;
        $failed['card_last4'] = $info['data']['object']['source']['last4'];

        $failed['amount'] = '$' . ($info['data']['object']['amount']/100);

        $user_email = '';
        if (array_key_exists('stripe_customer_id',$failed)){
            $name = 'not found';
        } else {
            $user = User::where('stripe_id', '=', $failed['stripe_customer_id'])->first();

            if ($user){
                $user_email = $user->email;
                $name = $user->name;
            }
            else{
                $name = 'not found';
            }
        }

        Mail::to($user_email)
                ->cc('accounting@nova-labs.org')
                ->bcc(['john.hoskins@nova-labs.org', ])
            ->send(new FailedCharge($name, $failed));
    }

}