@extends('layouts.app')

@section('title', __('people.people'))

@section('content')

    <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-hover" id="results-table">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-nowrap">@lang('people.family_name') <a href="javascript:;" class="sort" data-field="family_name">@icon(sort)</a></th>
                    <th>@lang('people.name') <a href="javascript:;" class="sort" data-field="name">@icon(sort)</a></th>
                    <th class="text-nowrap">@lang('people.date_of_birth') <a href="javascript:;" class="sort" data-field="date_of_birth">@icon(sort)</a></th>
                    <th>@lang('people.nationality') <a href="javascript:;" class="sort" data-field="nationality">@icon(sort)</a></th>
                    <th class="text-nowrap">@lang('people.police_no')</th>
                    <th class="text-nowrap">@lang('people.case_no')</th>
                    <th class="text-nowrap">@lang('people.med_no')</th>
                    <th class="text-nowrap">@lang('people.reg_no')</th>
                    <th class="text-nowrap">@lang('people.sec_card_no')</th>
                    <th class="text-nowrap">@lang('people.temp_no')</th>
                    <th>@lang('people.languages') <a href="javascript:;" class="sort" data-field="languages">@icon(sort)</a></th>
                    <th>@lang('people.remarks') <a href="javascript:;" class="sort" data-field="remarks">@icon(sort)</a></th>
                    {{-- TODO <th>@lang('people.helper') <a href="javascript:;" class="sort" data-field="helper">@icon(sort)</a></th> --}}
                    <th>@lang('app.registered') <a href="javascript:;" class="sort" data-field="created_at">@icon(sort)</a></th>
                </tr>
                <tr id="filter">
                    <th></th>
                    <th>{{ Form::text('family_name', !empty($filter['family_name']) ? $filter['family_name'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('name', !empty($filter['name']) ? $filter['name'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('date_of_birth', !empty($filter['date_of_birth']) ? $filter['date_of_birth'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('nationality', !empty($filter['nationality']) ? $filter['nationality'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('police_no', !empty($filter['police_no']) ? $filter['police_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('case_no', !empty($filter['case_no']) ? $filter['case_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('medical_no', !empty($filter['medical_no']) ? $filter['medical_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('registration_no', !empty($filter['registration_no']) ? $filter['registration_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('section_card_no', !empty($filter['section_card_no']) ? $filter['section_card_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('temp_no', !empty($filter['temp_no']) ? $filter['temp_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('languages', !empty($filter['languages']) ? $filter['languages'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    <th>{{ Form::text('remarks', !empty($filter['remarks']) ? $filter['remarks'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    {{-- TODO <th></th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="13">Loading, please wait...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-auto align-items-center">
            <ul class="pagination pagination-sm" id="paginator"></ul>
        </div>
        <div class="col align-items-center"><small id="paginator-info"></small></div>
        <div class="col-auto align-items-center mb-4">
            <button class="btn btn-secondary btn-sm" id="reset-filter">Reset filter</button>
        </div>
    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var filterUrl = '{{ route('people.filter') }}';
@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection