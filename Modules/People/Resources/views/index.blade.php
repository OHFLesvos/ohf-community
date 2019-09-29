@extends('layouts.app')

@section('title', __('people::people.people'))

@section('content')
    
    {!! Form::open(['route' => 'people.bulkAction']) !!}

        @can('manage-people', Modules\People\Entities\Person::class)
            <div class="card mb-4 bg-light" id="selected_actions_container" style="display:none;">
                <div class="card-header">@lang('app.bulk_action') (<span id="selected_count">0</span> @lang('people::people.persons'))</div>
                <div class="card-body">
                    <button 
                        class="btn btn-secondary btn-sm" 
                        type="submit"
                        name="selected_action" 
                        value="delete" 
                        data-bulk-min="1" 
                        onclick="return confirm('@lang('people::people.really_delete_these_persons')')"
                    >@lang('app.delete')</button>
                    <button 
                        class="btn btn-secondary btn-sm"
                        type="submit"
                        name="selected_action" 
                        value="merge" 
                        data-bulk-min="2" 
                        onclick="return confirm('@lang('people::people.really_merge_these_persons')')"
                    >@lang('app.merge')</button>
                </div>
            </div>
        @endcan

        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered table-hover" id="results-table">
                <thead>
                    <tr>
                        @can('manage-people', Modules\People\Entities\Person::class)
                            <th rowspan="2" id="bulk_select_on"></th>
                        @endcan
                        <th rowspan="2"></th>
                        <th class="text-nowrap">@lang('people::people.family_name') <a href="javascript:;" class="sort" data-field="family_name">@icon(sort)</a></th>
                        <th>@lang('people::people.name') <a href="javascript:;" class="sort" data-field="name">@icon(sort)</a></th>
                        <th class="text-nowrap">@lang('people::people.date_of_birth') <a href="javascript:;" class="sort" data-field="date_of_birth">@icon(sort)</a></th>
                        <th>@lang('people::people.nationality') <a href="javascript:;" class="sort" data-field="nationality">@icon(sort)</a></th>
                        <th class="text-nowrap">@lang('people::people.police_no')</th>
                        <th>@lang('people::people.languages') <a href="javascript:;" class="sort" data-field="languages">@icon(sort)</a></th>
                        <th>@lang('people::people.remarks') <a href="javascript:;" class="sort" data-field="remarks">@icon(sort)</a></th>
                        <th rowspan="2" class="align-top">@lang('app.registered') <a href="javascript:;" class="sort" data-field="created_at">@icon(sort)</a></th>
                    </tr>
                    <tr id="filter">
                        <th>{{ Form::text('family_name', !empty($filter['family_name']) ? $filter['family_name'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                        <th>{{ Form::text('name', !empty($filter['name']) ? $filter['name'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                        <th>{{ Form::text('date_of_birth', !empty($filter['date_of_birth']) ? $filter['date_of_birth'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                        <th>{{ Form::text('nationality', !empty($filter['nationality']) ? $filter['nationality'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                        <th>{{ Form::text('police_no', !empty($filter['police_no']) ? $filter['police_no'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                        <th>{{ Form::text('languages', !empty($filter['languages']) ? $filter['languages'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                        <th>{{ Form::text('remarks', !empty($filter['remarks']) ? $filter['remarks'] : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="11">Loading, please wait...</td>
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

    {!! Form::close() !!}

@endsection

@section('script')
    var filterUrl = '{{ route('people.filter') }}';
@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection