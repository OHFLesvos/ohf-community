@extends('layouts.app', ['wide_layout' => false])

@section('title', __('cmtyvol.import_export_data'))

@section('content')
    <div class="row">
        @can('import', App\Models\CommunityVolunteers\CommunityVolunteer::class)
            <div class="col-md">
                {!! Form::open(['route' => 'cmtyvol.doImport', 'files' => true]) !!}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">@lang('app.import')</div>
                        <div class="card-body">
                            {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv', 'class' => 'import-form-file' ], __('app.choose_file')) }}
                        </div>
                        <table class="import-form-header-mapping table d-none" data-query="{{ route('api.cmtyvol.getHeaderMappings') }}">
                            <thead>
                                <th>@lang('app.field_to_import')</th>
                                <th>@lang('app.field_in_database')</th>
                                <th>@lang('app.add_to_existing_values')</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="card-footer text-right">
                            <x-form.bs-submit-button :label="__('app.import')" icon="upload"/>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        @endcan
        @can('export', App\Models\CommunityVolunteers\CommunityVolunteer::class)
            <div class="col-md">
                {!! Form::open(['route' => 'cmtyvol.doExport']) !!}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">@lang('app.export')</div>
                        <div class="card-body">
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
                        </div>
                        <div class="card-footer text-right">
                            <x-form.bs-submit-button :label="__('app.export')" icon="download"/>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        @endcan
    </div>
@endsection

