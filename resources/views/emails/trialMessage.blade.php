@component('mail::message')
    # Test

    Charge went through
    <div>
        Stripe info for {{$name}}
    </div>
    Event id: {{$event['id']}}

    Customer id: {{$event['customer_id']}}

    Charge amount: {{$event['amount']}}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
