@extends('layouts.app')

@section('title', __('library.export_borrowers'))

@section('content')

    {!! Form::open(['route' => 'library.lending.doExport']) !!}
        <div class="mb-3">
            {{ Form::bsRadioList('format', $formats, $format, __('app.file_format')) }}
        </div>
        <div class="mb-3">
            {{ Form::bsRadioList('selection', $selections, $selection, __('app.selection')) }}
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.export'), 'download') }}
        </p>
    {!! Form::close() !!}

@endsection

