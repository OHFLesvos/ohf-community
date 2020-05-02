@extends('layouts.app')

@section('title', __('app.report'))

@section('content')

    <div id="fundraising-app">
        <donors-report>
            @lang('app.loading')
        </donors-report>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
