@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.monthly_summary'))

@section('content')

    <div id="app">
        Period: {{ $from->toDateString() }} &ndash; {{ $to->toDateString() }}</p>

        Coupons handed out: {{ $coupons_handed_out }}<br>

        @foreach($coupon_types_handed_out as $type)
            {{ $type['name'] }}: {{ $type['count'] }}<br>
        @endforeach

        Unique visitors: {{ $unique_visitors }}<br>
        Total visitors: {{ $total_visitors }}<br>
        Days active: {{ $days_active }}<br>
        Average visitors per day: {{ $days_active > 0 ? round($total_visitors / $days_active) : 0 }}<br>
        New registrations: {{ $new_registrations }}<br>
    </div>
@endsection
