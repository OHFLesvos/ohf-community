@extends('layouts.app')

@section('title', 'Bank Maintenance')

@section('content')

    {!! Form::open(['route' => ['bank.updateMaintenance']]) !!}

        <div class="card mb-4">
            <div class="card-header">Cleanup database</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsCheckbox('cleanup_no_transactions_since', null, null, 'Remove records not having any transactions since ' . $months_no_transactions_since . ' months (' . $people_without_transactions_since . ' persons)') }}
                        {{ Form::bsCheckbox('cleanup_no_number', null, null, 'Remove records not having any number registered (' . $people_without_number . ' persons)') }}
                        <br>
                        {{ Form::bsSubmitButton('Cleanup') }}
                    </div>
                </div>
            </div>
        </div>

    {!! Form::close() !!}
    
@endsection
