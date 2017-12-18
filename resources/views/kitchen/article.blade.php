@extends('layouts.app')

@section('title', $article->name . ' (' . $article->type . ')')

@section('content')

    <div class="form-row justify-content-end">
        <div class="col col-auto">
            <input id="chart_date_start" type="date" class="form-control form-control-sm" value="{{ $chart_date_start->toDateString() }}" max="{{ Carbon\Carbon::yesterday()->toDateString() }}">
        </div>
        <div class="col col-auto">
            <input id="chart_date_end" type="date" class="form-control form-control-sm" value="{{ $chart_date_end->toDateString() }}" max="{{ Carbon\Carbon::today()->endOfMonth()->toDateString() }}">
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="mx-2">
        <canvas id="chart" style="height: 300px"></canvas>
    </div>

    <table class="table table-bordered md my-4">
        <thead>
            <tr>
                @for ($date = clone $date_start; $date->lt( (clone $date_start)->addDays(7) ); $date->addDay())
                    <th class="px-0 text-center" style="width: 14.25%">{{ $date->format('D') }}</th>
                @endfor
            </tr>
        </thead>
        <tr>
            @for ($date = clone $date_start; $date->lte($date_end); $date->addDay())
                <td class="text-center" style="height: 4.5em; position: relative; vertical-align: middle">
                    <span class="text-muted position-absolute p-0 m-0" style="right: 5px; top: 0; line-height: 1em;"><small>{{ $date->day }}</small></span>
                    <span class="lead">{{ $article->dayTransactions($date) }}</span>
                </td>
                @if ( $date->dayOfWeek == Carbon\Carbon::SUNDAY ) </tr><tr> @endif
            @endfor
        </tr>
    </table>

    <script src="{{asset('js/Chart.min.js')}}?v={{ $app_version }}"></script>

@endsection

@section('script')
    var url = '{{ route('kitchen.transactions', $article) }}';
    var title = '{{ $article->name }} ({{ $article->type }}) per day';

    $(function(){
        drawChart();
        $('#chart_date_start').on('change', drawChart);
        $('#chart_date_end').on('change', drawChart);
    })

    function drawChart(){
        var parent = $('#chart').parent();
        parent.empty();
        $(parent).append('<canvas id="chart" style="height: 300px"><canvas>');

        var elem = document.getElementById("chart");
        var from = $('#chart_date_start').val();
        var to = $('#chart_date_end').val();
        var ajaxUrl = url + "?t";
        if (from) {
            ajaxUrl += '&from=' + from;
        }
        if (to) {
            ajaxUrl += '&to=' + to;
        }
        $.get(ajaxUrl)
            .done(function(result){
                var labels = Object.keys(result.data);
                var label = result.name;
                var data = Object.values(result.data);
                var unit = result.unit;
                simpleBarChart(elem, title, labels, label, data, unit);
        });
    }

    function simpleBarChart(elem, title, labels, label, data, unit) {
        var ctx = elem.getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    backgroundColor: '#' + window.coloePalette[0],
                    borderColor: '#' + window.coloePalette[0],
                    fill: false,
                    data: data
                }]
            },
            options: {
                title: {
                    display: true,
                    text: title
                },
                legend: {
                    display: false,
                    position: 'bottom'
                },
                elements: {
                    line: {
                        tension: 0
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Day'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: unit
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
            }  
        });
    }
@endsection