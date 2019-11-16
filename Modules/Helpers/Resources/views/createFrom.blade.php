@extends('layouts.app')

@section('title', __('people::people.register_helper'))

@section('content')

    <div id="helper-app">
        {!! Form::open(['route' => ['people.helpers.storeFrom']]) !!}
            <person-search
                name="person_id"
                api-url="{{ route('api.people.filterPersons') }}"
                placeholder="@lang('people::people.search_existing_person')"
                searching-label="@lang('app.searching')"
                found-label="@lang('people::people.select_existing_person_or_register_new_one')"
                not-found-label="@lang('app.not_found')"
                @if($errors->has('person_id')) invalid="{{$errors->first('person_id')}}" @endif
            >
                <template v-slot:found>
                    {{ Form::bsSubmitButton(__('people::people.use_existing_person')) }}
                </template>
                <template v-slot:not-found>
                    <a href="{{ route('people.helpers.create') }}" class="btn btn-secondary">@icon(plus-circle) @lang('people::people.register_new_person')</a>
                </template>
                @lang('app.loading')
                </person-search>
        {!! Form::close() !!}
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/helpers.js') }}?v={{ $app_version }}"></script>
@endsection
