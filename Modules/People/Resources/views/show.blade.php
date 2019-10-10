@extends('layouts.app')

@section('title', __('people::people.view_person'))  {{-- $person->family_name . ' ' . $person->name --}}

@section('content')

    @if(optional($person->helper)->isActive)
        @component('components.alert.info')
            @lang('people::people.person_registered_as_helper')
        @endcomponent
    @endif

    @isset($person->remarks)
        @component('components.alert.info')
            @lang('people::people.remarks'): {{ $person->remarks }}
        @endcomponent
    @endisset

    <div class="row mb-3">
        <div class="col-md">
            {{-- Properties --}}
            @include('people::snippets.properties', [ 'showRouteName' => 'people.show' ])
        </div>
        <div class="col-md">
            {{-- Card --}}
            @include('people::snippets.card')
        </div>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection