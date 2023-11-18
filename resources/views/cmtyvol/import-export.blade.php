@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Export community volunteer data'))

@section('content')
    <div class="row">
        @can('export', App\Models\CommunityVolunteers\CommunityVolunteer::class)
            <div class="col-md">
                {!! Form::open(['route' => 'cmtyvol.doExport']) !!}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">{{ __('Export') }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="mb-3">
                                        {{ Form::bsRadioList('format', $formats, $format, __('File format')) }}
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::bsRadioList('work_status', $work_statuses, $work_status, __('Work status')) }}
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::bsRadioList('column_set', $columnt_sets, $columnt_set, __('Columns')) }}
                                    </div>
                                </div>
                                <div class="col-sm mb-3">
                                    <div class="mb-3">
                                        {{ Form::bsRadioList('sorting', $sorters, $sorting, __('Order')) }}
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::bsRadioList('orientation', ['portrait' => __('Portrait'), 'landscape' => __('Landscape')], 'portrait', __('Orientation')) }}
                                    </div>
                                    <p>{{ Form::bsCheckbox('fit_to_page', 1, null, __('Fit to page')) }}</p>
                                    <p>{{ Form::bsCheckbox('include_portraits', 1, null, __('Include Portraits')) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <x-form.bs-submit-button :label="__('Export')" icon="download"/>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        @endcan
    </div>
@endsection

