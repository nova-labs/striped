@component('mail::message')
    # Billing problem

    Member {{$name}} has an invoice that has failed for the second time on {{$failed['time']}}.

    Please follow up with the member to hopefully resolve this issue.

    Time: {{$failed['time']}}
    Amount: {{$failed['amount']}}

    PDF of invoice is here: {{$failed['link']}}

    Thanks,

    Nova Labs Automated Stripe Monitoring

@endcomponent
