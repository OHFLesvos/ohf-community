@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.monthly_summary'))

@section('content')

    <div class="row">
        <div class="col-sm">
            <h2 class="display-4 mb-4">{{ $monthDate->formatLocalized('%B %Y') }}</h2>
        </div>
        <div class="col-sm-auto">
            @if(sizeof($months) > 0)
                {{ Form::bsSelect('timerange', $months, $monthDate->format('Y-m'), [ 'id' => 'timerange' ], '') }}
            @endif
        </div>
    </div>

    <div id="app">
        @php
            $current_average_visitors_per_day = $current_days_active > 0 ? round($current_total_visitors / $current_days_active) : 0;
            $previous_average_visitors_per_day = $previous_days_active > 0 ? round($previous_total_visitors / $previous_days_active) : 0;

            $current_average_registrations_per_day = $current_days_active > 0 ? round($current_new_registrations / $current_days_active) : 0;
            $previous_average_registrations_per_day = $previous_days_active > 0 ? round($previous_new_registrations / $previous_days_active) : 0;

            $data = [
                [
                    'label' => 'Coupons handed out',
                    'value' => $current_coupons_handed_out,
                    'percent' => [$previous_coupons_handed_out, $current_coupons_handed_out],
                    'list' => $current_coupon_types_handed_out,
                    'ytd' => $year_coupons_handed_out,
                ],
                [
                    'label' => 'Days active',
                    'value' => $current_days_active,
                    'percent' => [$previous_days_active, $current_days_active],
                    'ytd' => $year_days_active,
                ],
                [
                    'label' => 'Unique visitors',
                    'value' => $current_unique_visitors,
                    'percent' => [$previous_unique_visitors, $current_unique_visitors],
                    'ytd' => $year_unique_visitors,
                ],
                [
                    'label' => 'Total visitors',
                    'value' => $current_total_visitors,
                    'percent' => [$previous_total_visitors, $current_total_visitors],
                    'ytd' => $year_total_visitors,
                ],
                [
                    'label' => 'Average visitors / day',
                    'value' => $current_average_visitors_per_day,
                    'percent' => [$previous_average_visitors_per_day, $current_average_visitors_per_day],
                ],
                [
                    'label' => 'New registrations',
                    'value' => $current_new_registrations,
                    'percent' => [$previous_new_registrations, $current_new_registrations],
                    'ytd' => $year_new_registrations,
                ],
                [
                    'label' => 'Average registrations / day',
                    'value' => $current_average_registrations_per_day,
                    'percent' => [$previous_average_registrations_per_day, $current_average_registrations_per_day],
                ],
            ];
        @endphp

        <div class="row">
        {{-- <div class="card-columns"> --}}
            @foreach($data as $item)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="card text-dark bg-light">
                        <div class="card-body text-right">
                            <big class="display-4">{{ number_format($item['value']) }}</big>
                            @isset($item['percent'])
                                @php
                                    list($prev, $cur) = $item['percent'];
                                    $percent_val = $prev != 0 ? round((($cur - $prev) / $prev) * 100) : null;
                                @endphp
                                @isset($percent_val)
                                &nbsp; <small class="@if($percent_val > 0) text-success @elseif($percent_val < 0) text-danger @endif">
                                    {{ abs($percent_val) }}% 
                                    @if($percent_val > 0)
                                        @icon(caret-up)
                                    @elseif($percent_val < 0)
                                        @icon(caret-down)
                                    @endif
                                </small>
                                @endisset
                            @endisset
                            <br>
                            <small class="text-uppercase">{{ $item['label'] }}</small>
                            @isset($item['ytd'])
                            <br><small class="text-muted" title="Year-to-date">{{ number_format($item['ytd']) }} (YTD)</small>
                            @endisset
                        </div>
                        @isset($item['list'])
                            <ul class="list-group list-group-flush">
                                @foreach($item['list'] as $li)
                                    <li class="list-group-item">
                                        {{ $li['label'] }}
                                        <span class="pull-right">{{ number_format($li['value']) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endisset
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection

@section('script')
$(function(){
    $('#timerange').on('change', function(){
        var val = $(this).val().split('-');
        document.location = '{{ route('reporting.monthly-summary') }}?year=' + val[0] + '&month=' + val[1];
    });
});
@endsection