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
use App\Mail\FailedSecondInvoice;
use App\User;

class HandleFailedInvoice implements ShouldQueue
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
        $info = $this->webhookCall->payload;

        if($info['data']['object']['attempt_count'] == 2){
            $failed = [];
            $failed['stripe_customer_id'] = $info['data']['object']['customer'];

            $user = User::where('stripe_id', '=', $failed['stripe_customer_id'])->first();
            $name = $user->name;

            $failed['link'] = $info['data']['object']['hosted_invoice_url'];
            $failed['amount'] = '$' . ($info['data']['object']['amount_due']/100);
            $failed['time'] = Carbon::createFromTimestamp($info['data']['object']['date'])
                ->timezone('America/New_York')->toDayDateTimeString();
            $failed['amount'] = '$' . ($info['data']['object']['amount_due']/100);

            Mail::to('membership@nova-labs.org')
                ->cc('accounting@nova-labs.org')
                ->bcc(['john.hoskins@nova-labs.org', ])
                ->send(new FailedSecondInvoice($name, $failed));

        }
    }
}