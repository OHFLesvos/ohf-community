@extends('dashboard.widgets.base')

@section('widget-title', __('app.changelog'))

@section('widget-subtitle')
    @lang('app.version'): <strong>{{ $app_version }}</strong>
@endsection

@section('widget-content')
    <div class="card-body pb-2">
        <p>@lang('app.changelog_link_desc', ['link' => route('changelog')])</p>
    </div>
@endsection