@extends('layouts.app')

@section('title', __('people::people.people'))

@section('content')
    
    {{-- {!! Form::open(['route' => 'people.bulkAction']) !!}
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
    {!! Form::close() !!} --}}

    <div id="people-app">
        @php
            $fields = [
                [
                    'key' => 'name',
                    'label' => __('app.name'),
                    'sortable' => true,
                ],
                [
                    'key' => 'family_name',
                    'label' => __('people::people.family_name'),
                    'sortable' => true,
                ],
                [
                    'key' => 'date_of_birth',
                    'label' => __('people::people.date_of_birth'),
                    'sortable' => true,
                ],
                [
                    'key' => 'nationality',
                    'label' => __('people::people.nationality'),
                    'sortable' => true,
                ],
                [
                    'key' => 'police_no',
                    'label' => __('people::people.police_no'),
                    'sortable' => false,
                ],
                [
                    'key' => 'languages',
                    'label' => __('people::people.languages'),
                    'sortable' => true,
                ],
                [
                    'key' => 'remarks',
                    'label' => __('people::people.remarks'),
                    'sortable' => true,
                ],
            ];
        @endphp
        <people-table 
            id="peopleTable"
            :fields='@json($fields)'
            api-url="{{ route('api.people.index') }}"
            default-sort-by="name"
            empty-text="@lang('people::people.no_persons_found')"
            filter-placeholder="@lang('people::people.bank_search_text')"
            :items-per-page="10"
        ></people-table>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection