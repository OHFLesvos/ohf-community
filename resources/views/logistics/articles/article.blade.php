@extends('layouts.app')

@section('title', $article->name . ' (' . $article->type . ')')

@section('content')

    <div class="form-row justify-content-end">
        <div class="col col-lg-auto mb-2">
            <input id="date_from" type="date" class="form-control form-control-sm" value="{{ $date_from->toDateString() }}" max="{{ Carbon\Carbon::yesterday()->toDateString() }}">
        </div>
        <div class="col col-lg-auto">
            <input id="date_to" type="date" class="form-control form-control-sm" value="{{ $date_to->toDateString() }}" max="{{ Carbon\Carbon::today()->endOfMonth()->toDateString() }}">
        </div>
    </div>

    <div class="mx-2" id="dayChart"></div>
    <div class="mx-2" id="weekDayChart"></div>
    
    <script src="{{ asset('js/Chart.min.js') }}?v={{ $app_version }}"></script>
@endsection

@section('script')

    $(function(){
        drawCharts();
        $('#date_from').on('change', drawCharts);
        $('#date_to').on('change', drawCharts);
    })

    function drawCharts() {
        drawDayChart();
        drawWeekDayAverageChart();
    }

    function drawDayChart(){
        var url = '{{ route('logistics.articles.transactionsPerDay', $article) }}';
        var title = '{{ $article->name }} ({{ $article->type }}) per day';

        var parent = $('#dayChart');
        parent.empty();
        $(parent).append('<canvas style="height: 300px"><canvas>');

        var elem = parent.children().get(0);
        var from = $('#date_from').val();
        var to = $('#date_to').val();
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

    function drawWeekDayAverageChart(){
        var url = '{{ route('logistics.articles.avgTransactionsPerWeekDay', $article) }}';
        var title = 'Average {{ $article->name }} ({{ $article->type }}) per weekday';

        var parent = $('#weekDayChart');
        parent.empty();
        $(parent).append('<canvas style="height: 300px"><canvas>');

        var elem = parent.children().get(0);
        var from = $('#date_from').val();
        var to = $('#date_to').val();
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