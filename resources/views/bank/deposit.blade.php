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

        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Yesterday</th>
                    <th>Today</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($projects as $project)
                <tr>
                    <td><a href="{{ route('bank.project', $project) }}">{{ $project->name }}</a></td>
                    <td>{{ $project->dayTransactions(Carbon\Carbon::today()->subDays(1)) }}</td>
                    <td>{{ $project->dayTransactions(Carbon\Carbon::today()) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4 mb-2">
            <canvas id="chart" style="height: 400px"></canvas>
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
        type: 'line',
        data: {
            labels: [@for ($i = 30; $i >= 0; $i--) "{{ Carbon\Carbon::today()->subDays($i)->format('D j. M') }}", @endfor],
            datasets: [@foreach($projects as $project){
                label: "{{ $project->name }}",
                backgroundColor: '#' + window.coloePalette[{{ $loop->index }} % window.coloePalette.length],
                borderColor: '#' + window.coloePalette[{{ $loop->index }} % window.coloePalette.length],
                fill: false,
                data: [ @for ($i = 30; $i >= 0; $i--) {{ $project->dayTransactions(Carbon\Carbon::today()->subDays($i)) }}, @endfor ]
            }, @endforeach
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Deposits per project'
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            elements: {
                line: {
                    tension: 0
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }  
    });
@endsection