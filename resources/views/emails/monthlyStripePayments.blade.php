@component('mail::message')
# Monthly stripe Payments report

Here are the details of last month's charges (and refunds)
<div>
    Stripe payments for {{$span}}
</div>

<table width="800">
    <thead>
    <tr>
        <th>#</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Details</th>
    </tr>
    </thead>
    <tbody>
    @foreach($charges as $charge)
        <tr>
            <th scope="row">{{$charge->id}}</th>
            <td>{{$charge->date}}</td>
            <td>{{   number_format( $charge->amount/100,2)}}</td>
            <td>{{ $charge->customer }}
                @if (isset($charge->stripe_customer['id']))
                    {{$charge->stripe_customer['name'] }}
                @else
                    {{$charge->customer_id }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
Total : {{number_format( $total/100,2)}}
<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
