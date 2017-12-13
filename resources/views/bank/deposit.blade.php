@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    {!! Form::open(['route' => ['bank.storeDeposit']]) !!}
    <div class="card mb-4">
        <div class="card-header">Deposit Drachma</div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-sm mb-2 mb-md-0">
                    <div class="form-group">
                        {{ Form::select('project', $projectList, null, [ 'placeholder' => 'Select project...', 'class' => 'form-control'.($errors->has('project') ? ' is-invalid' : ''), 'autofocus', 'required' ]) }}
                        @if ($errors->has('project'))
                            <span class="invalid-feedback">{{ $errors->first('project') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm mb-2 mb-md-0">
                    {{ Form::bsNumber('value', null, [ 'placeholder' => 'Amount', 'required' ], '') }}
                </div>
                <div class="col-sm mb-2 mb-md-0">
                    {{ Form::bsDate('date', \Carbon\Carbon::now()->toDateString(), [ 'required', 'max' => \Carbon\Carbon::now()->toDateString() ], '') }}
                </div>
                <div class="col-sm-auto">
                    {{ Form::bsSubmitButton('Add', 'plus-circle') }}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    {{-- List of projects, with cumulated deposits --}}
    @if( ! $projects->isEmpty() )

        <div>
            <canvas id="chart" style="height: 400px"></canvas>
        </div>

        <h2 class="display-4 mb-3">Project Details</h2>
        <div class="row mt-4">
            @foreach ($projects as $project)
                <div class="col-md">
                    <div class="card mb-4">
                        <div class="card-header">{{ $project->name }}</div>
                        <div class="card-body p-0 m-0">
                            <table class="table table-bordered md m-0">
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
                                            <span class="lead">{{ $project->dayTransactions($date) }}</span>
                                        </td>
                                        @if ( $date->dayOfWeek == Carbon\Carbon::SUNDAY ) </tr><tr> @endif
                                    @endfor
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @if( $loop->index % 2 == 1)
                    <div class="w-100 d-none d-md-block"></div>
                @endif
            @endforeach
        </div>
    @else
        @component('components.alert.info')
            No projects found.
        @endcomponent
    @endif

    <script src="{{asset('js/Chart.min.js')}}?v={{ $app_version }}"></script>

@endsection

@section('script')
    var ctx = document.getElementById("chart").getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [@for ($i = 7; $i >= 0; $i--) "{{ Carbon\Carbon::today()->subDays($i)->format('D j. M') }}", @endfor],
            datasets: [@foreach($projects as $project){
                label: "{{ $project->name }}",
                backgroundColor: '#' + window.coloePalette[{{ $loop->index }} % window.coloePalette.length],
                borderColor: '#' + window.coloePalette[{{ $loop->index }} % window.coloePalette.length],
                fill: false,
                data: [ @for ($i = 7; $i >= 0; $i--) {{ $project->dayTransactions(Carbon\Carbon::today()->subDays($i)) }}, @endfor ]
            }, @endforeach
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Projects'
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            responsive: true,
            maintainAspectRatio: false,
        }  
    });
@endsection