@extends('bank.layout')

@section('title', __('people.bank'))

@section('wrapped-content')

    {!! Form::open(['route' => ['bank.storeDeposit']]) !!}
    <div class="card mb-4">
        <div class="card-header">@lang('people.deposit_drachma')</div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-sm mb-2 mb-md-0">
                    <div class="form-group">
                        {{ Form::select('project', $projectList, null, [ 'placeholder' => __('people.select_project'), 'class' => 'form-control'.($errors->has('project') ? ' is-invalid' : ''), 'autofocus', 'required' ]) }}
                        @if ($errors->has('project'))
                            <span class="invalid-feedback">{{ $errors->first('project') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm mb-2 mb-md-0">
                    {{ Form::bsNumber('value', null, [ 'placeholder' => __('app.amount'), 'required' ], '') }}
                </div>
                <div class="col-sm mb-2 mb-md-0">
                    {{ Form::bsDate('date', \Carbon\Carbon::now()->toDateString(), [ 'required', 'max' => \Carbon\Carbon::now()->toDateString() ], '') }}
                </div>
                <div class="col-sm-auto">
                    {{ Form::bsSubmitButton(__('app.add'), 'plus-circle') }}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

@endsection