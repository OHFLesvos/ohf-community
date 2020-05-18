@extends('layouts.app')

@section('title', __('fundraising.show_donor'))

@section('content')
    <div id="fundraising-app">
        <donor-show-page
            :donor='@json($donorResource)'
            :channels='@json($channels)'
            :currencies='@json(config('fundraising.currencies'))'
            base-currency="{{ config('fundraising.base_currency') }}"
        >
            @lang('app.loading')
        </donor-show-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
