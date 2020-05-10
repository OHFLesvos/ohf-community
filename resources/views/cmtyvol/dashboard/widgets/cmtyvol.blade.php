@php
    $links = [
        [
            'url' => route('cmtyvol.index'),
            'title' =>  Auth::user()->can('manage-community-volunteers') ? __('app.manage') : __('app.view'),
            'icon' => Auth::user()->can('manage-community-volunteers') ? 'edit' : 'search',
            'authorized' => Auth::user()->can('viewAny', App\Models\CommunityVolunteers\CommunityVolunteer::class),
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('cmtyvol.community_volunteers'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('cmtyvol.we_have_n_community_volunteers', [
                'active' => $active,
            ])<br>
        </p>
    </div>
@endsection
