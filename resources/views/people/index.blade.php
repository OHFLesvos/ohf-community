@extends('layouts.app')

@section('title', 'People')

@section('content')

    <table class="table table-sm table-striped table-bordered table-hover table-responsive-md" id="results-table">
        <thead>
            <tr>
                <th>Name</th>
                <th class="text-nowrap">Family Name</th>
                <th class="text-nowrap">Case No.</th>
                <th class="text-nowrap">Med No.</th>
                <th class="text-nowrap">Reg No.</th>
                <th class="text-nowrap">Sec Card No.</th>
                <th>Nationality</th>
                <th>Languages</th>
                <th>Skills</th>
                <th>Remarks</th>
            </tr>
            <tr id="filter">
                <th>{{ Form::text('name', !empty($filter['name']) ? $filter['name'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('family_name', !empty($filter['family_name']) ? $filter['family_name'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('case_no', !empty($filter['case_no']) ? $filter['case_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('medical_no', !empty($filter['medical_no']) ? $filter['medical_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('registration_no', !empty($filter['registration_no']) ? $filter['registration_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('section_card_no', !empty($filter['section_card_no']) ? $filter['section_card_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('nationality', !empty($filter['nationality']) ? $filter['nationality'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('languages', !empty($filter['languages']) ? $filter['languages'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('skills', !empty($filter['skills']) ? $filter['skills'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('remarks', !empty($filter['remarks']) ? $filter['remarks'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="10">Loading, please wait...</td>
            </tr>
        </tbody>
    </table>
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
    <script src="{{asset('js/people.js')}}?v={{ $app_version }}"></script>
@endsection