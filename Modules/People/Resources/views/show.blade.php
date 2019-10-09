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
            @include('people::snippets.properties', [ 'showRouteName' => 'people.show' ])
        </div>
        <div class="col-md">

            @isset($person->card_no))
                @include('people::snippets.card')
            @endisset

        </div>
    </div>

@endsection
