@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.export'))

@section('content')
    {!! Form::open(['route' => 'bank.doExport']) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                {{ Form::bsRadioInlineList('format', $formats, $selectedFormat, __('app.file_format')) }}
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('app.export')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

