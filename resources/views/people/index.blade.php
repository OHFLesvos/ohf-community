@extends('layouts.app')

@section('title', __('people.people'))

@section('content')

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
                    'label' => __('people.family_name'),
                    'sortable' => true,
                ],
                [
                    'key' => 'date_of_birth',
                    'label' => __('people.date_of_birth'),
                    'sortable' => true,
                ],
                [
                    'key' => 'nationality',
                    'label' => __('people.nationality'),
                    'sortable' => true,
                ],
                [
                    'key' => 'police_no_formatted',
                    'label' => __('people.police_no'),
                    'sortable' => false,
                ],
                [
                    'key' => 'languages',
                    'label' => __('people.languages'),
                    'sortable' => true,
                ],
                [
                    'key' => 'remarks',
                    'label' => __('people.remarks'),
                    'sortable' => true,
                ],
            ];
        @endphp
        <people-table
            id="peopleTable"
            :fields='@json($fields)'
            api-url="{{ route('api.people.index') }}"
            default-sort-by="name"
            empty-text="@lang('people.no_persons_found')"
            filter-placeholder="@lang('people.bank_search_text')"
            :items-per-page="15"
            loading-label="@lang('app.loading')"
        ></people-table>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection