@extends('layouts.app')

@section('title', __('people.import_bank_data'))

@section('content')

    {!! Form::open(['route' => 'bank.doImport', 'files' => true]) !!}
        {{ Form::bsFile('file', [], __('app.choose_file')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.import'), 'upload') }}
        </p>
    {!! Form::close() !!}
    
@endsection

