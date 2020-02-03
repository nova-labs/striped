@component('mail::message')
# Weekly stripe churn report

Here are the details of last week's changes
<div>
    Stripe subscription changes for {{$span}}
</div>

<ul>
    @foreach($churn as $item)
        <li>
            @if($item->customer) {{$item->customer->name}} @else Not in DB @endif - {{$item->customer_id}}<br/>
            {{$item->event_date}}<br/>
            {{$item->output_text}}
        </li>
    @endforeach
</ul>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
