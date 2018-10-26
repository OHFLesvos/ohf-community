@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.monthly_summary'))

@section('content')

    <div id="app">
        Period: {{ $from->toDateString() }} &ndash; {{ $to->toDateString() }}</p>

        <h2 class="display-4 mb-4">Visitors & Distribution</h2>
        @php
            $data = [
                [
                    'label' => 'Coupons handed out',
                    'value' => $coupons_handed_out,
                ],
                [
                    'label' => 'Unique visitors',
                    'value' => $unique_visitors,
                ],
                [
                    'label' => 'Total visitors',
                    'value' => $total_visitors,
                ],
                [
                    'label' => 'Days active',
                    'value' => $days_active,
                ],
                [
                    'label' => 'Average visitors per day',
                    'value' => $days_active > 0 ? round($total_visitors / $days_active) : 0,
                ],
                [
                    'label' => 'New registrations',
                    'value' => $new_registrations,
                ],
            ];
        @endphp

        <div class="row">
            @foreach($data as $item)
                <div class="col-sm mb-3">
                    <div class="card text-dark bg-light text-right">
                        <div class="card-body">
                            <big class="display-4">{{ number_format($item['value']) }}</big><br>
                            <small class="text-uppercase">{{ $item['label'] }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        Coupons handed out: {{ number_format($coupons_handed_out) }}<br>

        @foreach($coupon_types_handed_out as $type)
            {{ $type['name'] }}: {{ number_format($type['count']) }}<br>
        @endforeach

        Unique visitors: {{ number_format($unique_visitors) }}
        
        <br>
        Total visitors: {{ number_format($total_visitors) }}<br>
        Days active: {{ $days_active }}<br>
        Average visitors per day: {{ $days_active > 0 ? number_format(round($total_visitors / $days_active)) : 0 }}<br>
        New registrations: {{ number_format($new_registrations) }}<br>
    </div>
@endsection
