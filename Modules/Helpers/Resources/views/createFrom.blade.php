@extends('layouts.app')

@section('title', __('people::people.register_helper'))

@section('content')

    <div id="helper-app">
        {!! Form::open(['route' => ['people.helpers.storeFrom']]) !!}
            <person-search
                name="person_id"
                api-url="{{ route('api.people.filterPersons') }}"
                placeholder="@lang('people::people.search_existing_person')"
            >
                {{ Form::bsSubmitButton(__('people::people.use_existing_person')) }}
                <template v-slot:not-found>
                    <a href="{{ route('people.helpers.create') }}" class="btn btn-secondary">@icon(plus-circle) @lang('people::people.register_new_person')</a>
                </template>
            </person-search>
        {!! Form::close() !!}
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/helpers.js') }}?v={{ $app_version }}"></script>
@endsection
