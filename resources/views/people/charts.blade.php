@extends('layouts.app')

@section('title', 'People Charts')

@section('buttons')
	{{ Form::bsButtonLink(route('people.index'), 'Close', 'times-circle') }}
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">Nationalities</div>
                <div class="card-body">
                    <canvas id="chartNationalities"></canvas>
                </div>
            </div>
        </div>
		
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">New registrations per day</div>
                <div class="card-body">
                    <canvas id="registrationsPerDay"></canvas>
                </div>
            </div>
        </div>
    </div>
	
@endsection

@section('script')
	var ctx = document.getElementById("chartNationalities").getContext('2d');
	var chartNationalities = new Chart(ctx, {
		type: 'pie',
		data: {
			labels: ["{!! implode('", "', array_keys($data['nationalities'])) !!}"],
			datasets: [{
				label: 'Transactions',
				data: [{!! implode(', ', $data['nationalities']) !!}],
				backgroundColor: ["{!! implode('", "', $colors) !!}"]
			}]
		},
		options: {
			legend: {
				display: true,
				position: 'left',
				labels: {
					generateLabels: function(chart) {
						var data = chart.data;
						if (data.labels.length && data.datasets.length) {
							return data.labels.map(function(label, i) {
								var meta = chart.getDatasetMeta(0);
								var ds = data.datasets[0];
								var arc = meta.data[i];
								var custom = arc && arc.custom || {};
								var getValueAtIndexOrDefault = Chart.helpers.getValueAtIndexOrDefault;
								var arcOpts = chart.options.elements.arc;
								var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
								var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
								var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);

								// We get the value of the current label
								var value = chart.config.data.datasets[arc._datasetIndex].data[arc._index];

								return {
									// Instead of `text: label,`
									// We add the value to the string
									text: label + " : " + value,
									fillStyle: fill,
									strokeStyle: stroke,
									lineWidth: bw,
									hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
									index: i
								};
							});
						} else {
							return [];
						}
					}
				}
			}
		}
    });

	// Registrations
    var ctx = document.getElementById("registrationsPerDay").getContext('2d');
    var registrationsPerDay = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ["{!! implode('", "', array_keys($data['registrations'])) !!}"],
			datasets: [{
				label: 'Registrations',
				data: [{!! implode(', ', $data['registrations']) !!}],
				backgroundColor: "rgba(0,74,127,0.4)"
			}],
		},
		options: {
			legend: {
				display: false,
				position: 'left'
			}
		}
    });
@endsection
