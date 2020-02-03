@component('mail::message')
# Monthly Stripe Subscription  report

Here are the members by plan last month
<div>
    Stripe subscribers for {{$span}}
</div>

@foreach($plan_counts as $plan_count)
<h3>{{$plan_count['name']}} - {{$plan_count['total']}}</h3>
@if($plan_count['total'])
<table width="800">
    <thead>
    <tr>
        <th style="text-align:left;">Subscriber Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($plan_count['subs'] as $sub)
        <tr>
            <th style="text-align:left;">{{$sub}}</th>
        </tr>
    @endforeach
    </tbody>
</table>
    <br>

@endif
@endforeach

<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
