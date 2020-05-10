@extends('layouts.app')

@section('title', __('cmtyvol.import_data'))

@section('content')

    {!! Form::open(['route' => 'cmtyvol.doImport', 'files' => true]) !!}
        {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv' ], __('app.choose_file')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.import'), 'upload') }}
        </p>
    {!! Form::close() !!}

@endsection

