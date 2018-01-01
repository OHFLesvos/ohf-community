@extends('layouts.app')

@section('title', 'People Charts')

@section('content')

    <div class="row mb-0 mb-sm-4">
        <div class="col-xl-6">
		
            <div class="card mb-2 mb-sm-0">
                <div class="card-body">
                    <div>
                        <canvas id="chartNationalities" style="height: 100px"></canvas>
                    </div>
                    <table class="table table-sm mb-0 mt-4">
                        @foreach ($data['nationalities'] as $k => $v)
                            <tr>
                                <td data-nationality="{{ $k }}">&nbsp;</td>
                                <td>{{ $k }}</td>
                                <td class="text-right">{{ $v }}</td>
								<td class="text-right">{{ round($v / array_sum(array_values($data['nationalities'])) * 100) }} %</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
			</div>
	
        </div>
        <div class="col-xl-6">

            <div class="card mb-2 mb-sm-0">
                <div class="card-body">
                    <div>
                        <canvas id="visitsPerWeek" style="height: 272px"></canvas>
                    </div>
                    <table class="table table-sm mb-0 mt-4">
                        <tr>
                            <td>Last week</td>
                            <td class="text-right">{{ $visits_last_week }}</td>
                        </tr>
                        <tr>
                            <td>This week</td>
                            <td class="text-right">{{ $visits_this_week }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-body">
            <div>
                <canvas id="registrationsPerDay" style="height: 350px"></canvas>
            </div>
            <table class="table table-sm mb-0 mt-4">
            </table>
        </div>
    </div>


	<script src="{{asset('js/Chart.min.js')}}?v={{ $app_version }}"></script>

@endsection

@section('script')

	var chartColors = {
		red: 'rgb(255, 99, 132)',
		orange: 'rgb(255, 159, 64)',
		yellow: 'rgb(255, 205, 86)',
		green: 'rgb(75, 192, 192)',
		blue: 'rgb(54, 162, 235)',
		purple: 'rgb(153, 102, 255)',
		grey: 'rgb(201, 203, 207)'
	};

	var ctx = document.getElementById("chartNationalities").getContext('2d');
	var chartNationalities = new Chart(ctx, {
		type: 'horizontalBar',
		data: {
			// labels: ["Nationalities"],
			datasets: [ @foreach ($data['nationalities'] as $k => $v)
            {
				label: '{{ $k }}',
				data: [ {{ $v }} ],
				backgroundColor: chartColors[Object.keys(window.chartColors)[{{ $loop->index }}]],
			},
            @endforeach ]
		},
		options: {
            title: {
                display: true,
                text: 'Nationalities'
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            },
			legend: {
				display: false,
				position: 'top'
			},
            tooltips: {
                enabled: false
            }
		}
    });
    @foreach ($data['nationalities'] as $k => $v)
        $('td[data-nationality="{{$k}}"]').css('background-color', chartColors[Object.keys(window.chartColors)[{{ $loop->index }}]]);
    @endforeach

	// Registrations
    var ctx = document.getElementById("registrationsPerDay").getContext('2d');
    var registrationsPerDay = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ["{!! implode('", "', array_keys($data['registrations'])) !!}"],
			datasets: [{
				label: 'Registrations',
				data: [{!! implode(', ', $data['registrations']) !!}],
				backgroundColor: window.chartColors.blue
			}],
		},
		options: {
            title: {
                display: true,
                text: 'New registrations per day'
            },
			legend: {
				display: false,
				position: 'left'
			},
            responsive: true,
            maintainAspectRatio: false,
		}
    });

    // Visits per week
    visitsPerWeek
    var ctx = document.getElementById("visitsPerWeek").getContext('2d');
    var registrationsPerDay = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["{!! implode('", "', array_keys($data['visits_per_week'])) !!}"],
            datasets: [{
                label: 'Visits',
                data: [{!! implode(', ', $data['visits_per_week']) !!}],
                backgroundColor: window.chartColors.blue
            }],
        },
        options: {
            title: {
                display: true,
                text: 'Visitors per week'
            },
            legend: {
                display: false,
                position: 'left'
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
@endsection
