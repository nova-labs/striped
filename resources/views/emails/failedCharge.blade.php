@component('mail::message')
    # Problem with your credit card at Nova Labs

    Hi {{$name}},

    We tried charging your Nova Labs membership dues to your credit card but the charge was declined.

    Transaction details: {{$failed['amount']}} to {{$failed['card_brand']}} ending with {{$failed['card_last4']}} expiring {{$failed['card_expiration']}}
    Error: {{$failed['failure_message']}} at {{$failed['time']}}

    Please update your card or call your bank to ensure your current card will not be declined.

    To update your card:

    1) Sign In to the Nova Labs Website - https://nova-labs.org/account/accounting.html
    2) Click on the blue "Change Credit Card" button
    3) Enter the new card details

    We need to resolve this issue to help you maintain your membership. If your situation has changed, would you please let us know by replying to this email?

    Thank you,

    The Nova Labs Membership Team
    <membership@nova-labs.org>

@endcomponent
