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
                    <th class="text-right">Average</th>
                    <th class="text-right">Highest</th>
                    <th class="text-right">Last month</th>
                    <th class="text-right">This month</th>
                    <th class="text-right">Last week</th>
                    <th class="text-right">This week</th>
                    <th class="text-right">Today</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($projects as $project)
                <tr>
                    <td><a href="{{ route('bank.project', $project) }}">{{ $project->name }}</a></td>
                    <td class="text-right">{{ $project->avgNumTransactions() }}</td>
                    <td class="text-right">{{ $project->maxNumTransactions() }}</td>
                    <td class="text-right">{{ $project->monthTransactions(Carbon\Carbon::today()->startOfMonth()->subMonth()) }}</td>
                    <td class="text-right">{{ $project->monthTransactions(Carbon\Carbon::today()) }}</td>
                    <td class="text-right">{{ $project->weekTransactions(Carbon\Carbon::today()->startOfWeek()->subWeek()) }}</td>
                    <td class="text-right">{{ $project->weekTransactions(Carbon\Carbon::today()) }}</td>
                    <td class="text-right">{{ $project->dayTransactions(Carbon\Carbon::today()) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div id="app" class="my-3">
            <line-chart title="Drachma deposited per day" ylabel="Drachma" url="{{ route('bank.depositStats') }}" :height=300></line-chart>
        </div>

    @else
        @component('components.alert.info')
            No projects found.
        @endcomponent
    @endif

@endsection
