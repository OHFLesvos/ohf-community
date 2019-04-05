@php
    $links = [
        [
            'url' => route('people.helpers.index'),
            'title' =>  Auth::user()->can('manage-helpers') ? __('app.manage') : __('app.view'),
            'icon' => Auth::user()->can('manage-helpers') ? 'pencil' : 'search',
            'authorized' => Auth::user()->can('list', Modules\Helpers\Entities\Helper::class),
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('helpers::helpers.helpers'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('people::people.we_have_n_helpers', [ 
                'active' => $active,
                'trial' => $trial,
            ])<br>
            @lang('people::people.n_helpers_on_waiting_list', [ 
                'num' => $applicants,
            ])<br>
        </p>
    </div>
@endsection