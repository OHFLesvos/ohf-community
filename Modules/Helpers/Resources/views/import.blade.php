@extends('layouts.app')

@section('title', __('people.import_helper_data'))

@section('content')

    {!! Form::open(['route' => 'people.helpers.doImport', 'files' => true]) !!}
        {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv' ], __('app.choose_file')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.import'), 'upload') }}
        </p>
    {!! Form::close() !!}
    
@endsection

