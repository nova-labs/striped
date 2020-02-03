<?php
/**
 * Created by PhpStorm.
 * User: johnhoskins
 * Date: 1/26/19
 * Time: 9:56 PM
 */



namespace App\Jobs\StripeWebhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\StripeWebhooks\StripeWebhookCall;

use Illuminate\Support\Facades\Mail;
use App\Mail\SuccessCharge;
use App\User;

class HandleSuccessfulCharge implements ShouldQueue
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

        //$info = json_decode($whole);

        $event = [];
        $event['id'] = $info['id'];
        $event['customer'] = $info['data']['object']['source']['name'];
        $event['customer_id'] = $info['data']['object']['source']['customer'];
        $event['amount'] = '$' . ($info['data']['object']['amount']/100);

        $user = User::where('stripe_id', '=', $event['customer_id'])->first();

        if ($user)
            $name = $user->name;
        else
            $name = 'not found';



        Mail::to([

            'jhoskins98@gmail.com',
        ])->send(new SuccessCharge($name, $event));
    }

}