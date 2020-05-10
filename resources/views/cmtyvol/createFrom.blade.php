@extends('layouts.app')

@section('title', __('cmtyvol.register'))

@section('content')

    @can('viewAny', App\Models\People\Person::class)
        <div id="cmtyvol-app">
            {!! Form::open(['route' => ['cmtyvol.storeFrom']]) !!}
                <person-search
                    name="person_id"
                    @if($errors->has('person_id')) invalid="{{$errors->first('person_id')}}" @endif
                >
                    <template v-slot:found>
                        {{ Form::bsSubmitButton(__('people.use_existing_person')) }}
                    </template>
                    <template v-slot:not-found>
                        <a href="{{ route('cmtyvol.create') }}" class="btn btn-secondary">@icon(plus-circle) @lang('people.register_new_person')</a>
                    </template>
                    @lang('app.loading')
                </person-search>
            {!! Form::close() !!}
        </div>
    @else
        @component('components.alert.warning')
            @lang('people.no_permission_to_search_existing_persons')
        @endcomponent
        <p>
            <a href="{{ route('cmtyvol.create') }}" class="btn btn-secondary">@icon(plus-circle) @lang('people.register_new_person')</a>
        </p>
    @endcan

@endsection

@section('footer')
    <script src="{{ asset('js/cmtyvol.js') }}?v={{ $app_version }}"></script>
@endsection
