@extends('layouts.app')

@section('title', 'Bank Deposits')

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

    @if( ! $transactions->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Project</th>
                    <th class="text-right">Value</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td title="{{ $transaction->created_at }}">
                        {{ $transaction->created_at->diffForHumans() }}
                        <small class="text-muted">by {{ $transaction->user->name }}</small>
                    </td>
                    <td>{{ $transaction->name }}</td>
                    <td class="text-right">{{ $transaction->value }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $transactions->links('vendor.pagination.bootstrap-4') }}
    @endif


@endsection
