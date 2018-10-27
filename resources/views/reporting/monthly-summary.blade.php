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
            $data = [
                [
                    'label' => 'Coupons handed out',
                    'value' => $coupons_handed_out,
                    'list' => $coupon_types_handed_out,
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
                    'label' => 'Average visitors / day',
                    'value' => $days_active > 0 ? round($total_visitors / $days_active) : 0,
                ],
                [
                    'label' => 'New registrations',
                    'value' => $new_registrations,
                ],
                [
                    'label' => 'Average registrations / day',
                    'value' => $days_active > 0 ? round($new_registrations / $days_active) : 0,
                ],
            ];
        @endphp

        {{-- <div class="row"> --}}
        <div class="card-columns">
            @foreach($data as $item)
                {{-- <div class="col-sm mb-3"> --}}
                    <div class="card text-dark bg-light">
                        <div class="card-body text-right">
                            <big class="display-4">{{ number_format($item['value']) }}</big><br>
                            <small class="text-uppercase">{{ $item['label'] }}</small>
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
                {{-- </div> --}}
            @endforeach`
        </div>
        {{-- </div> --}}

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