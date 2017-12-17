@extends('layouts.app')

@section('title', $article->name . ' (' . $article->type . ')')

@section('content')

    <div class="mt-4 mb-2">
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
    var ctx = document.getElementById("chart").getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [@for ($i = 30; $i >= 0; $i--) "{{ Carbon\Carbon::today()->subDays($i)->format('D j. M') }}", @endfor],
            datasets: [{
                label: "{{ $article->name }}",
                backgroundColor: '#' + window.coloePalette[0],
                borderColor: '#' + window.coloePalette[0],
                fill: false,
                data: [ @for ($i = 30; $i >= 0; $i--) {{ $article->dayTransactions(Carbon\Carbon::today()->subDays($i)) }}, @endfor ]
            }]
        },
        options: {
            title: {
                display: false,
                text: 'Deposits per project'
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
                            labelString: '{{ $article->unit }}'
                        }
                    }]
                }
        }  
    });
@endsection