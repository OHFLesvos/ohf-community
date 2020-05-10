@extends('layouts.app')

@section('title', __('cmtyvol.export_data'))

@section('content')

    {!! Form::open(['route' => 'cmtyvol.doExport']) !!}
        <div class="row">
            <div class="col-sm">
                <div class="mb-3">
                    {{ Form::bsRadioList('format', $formats, $format, __('app.file_format')) }}
                </div>
                <div class="mb-3">
                    {{ Form::bsRadioList('work_status', $work_statuses, $work_status, __('cmtyvol.work_status')) }}
                </div>
                <div class="mb-3">
                    {{ Form::bsRadioList('column_set', $columnt_sets, $columnt_set, __('people.column_set')) }}
                </div>
            </div>
            <div class="col-sm mb-3">
                <div class="mb-3">
                    {{ Form::bsRadioList('sorting', $sorters, $sorting, __('app.order')) }}
                </div>
                <div class="mb-3">
                    {{ Form::bsRadioList('orientation', ['portrait' => __('app.portrait'), 'landscape' => __('app.landscape')], 'portrait', __('app.orientation')) }}
                </div>
                <p>{{ Form::bsCheckbox('fit_to_page', 1, null, __('app.fit_to_page')) }}</p>
                <p>{{ Form::bsCheckbox('include_portraits', 1, null, __('people.include_portraits')) }}</p>
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.export'), 'download') }}
        </p>
    {!! Form::close() !!}

@endsection

