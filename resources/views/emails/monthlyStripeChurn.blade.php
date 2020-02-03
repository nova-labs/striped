@component('mail::message')
# Monthly stripe churn report

Here are the details of last month's changes
<div>
    Stripe subscription changes for {{$span}}
</div>

<table width="800">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Badge#</th>
        <th>change</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($churn as $item)
        <tr>
            <td>@if($item->customer) {{$item->customer->name}} @else Not in DB @endif - {{$item->customer_id}}</td>
            <td>@if($item->customer) {{$item->customer->email}} @else Not in DB @endif</td>
            <td>@if($item->customer) {{$item->customer->badge_number}} @else Not in DB @endif</td>
            <td>{{$item->output_text}}</td>
            <td>{{$item->event_date}}</td>
        </tr>
    @endforeach
    </tbody>

</table>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
