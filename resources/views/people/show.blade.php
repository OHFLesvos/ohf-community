@extends('layouts.app', ['wide_layout' => false])

@section('title', __('people.view_person'))

@section('content')

    {{-- Community volunteer --}}
    @if($person->linkedCommunityVolunteer() !== null)
        <x-alert type="info">
            @lang('people.person_registered_as_community_volunteer')
        </x-alert>
    @endif

    {{-- Remarks --}}
    @isset($person->remarks)
        <x-alert type="info">
            @lang('people.remarks'): {{ $person->remarks }}
        </x-alert>
    @endisset

    <div class="row mb-3">
        <div class="col-md">
            {{-- Properties --}}
            @include('people.snippets.properties', [ 'showRouteName' => 'people.show' ])
        </div>
        <div class="col-md">
            {{-- Cards --}}
            @include('people.snippets.card')
        </div>
    </div>

    {{-- <hr> --}}
    <p class="text-right">
        <small>@lang('app.created'): {{ $person->created_at->diffForHumans() }} <small class="text-muted">{{ $person->created_at }}</small></small><br>
        <small>@lang('app.last_updated'): {{ $person->updated_at->diffForHumans() }} <small class="text-muted">{{ $person->updated_at }}</small></small></small>
    </p>

@endsection
