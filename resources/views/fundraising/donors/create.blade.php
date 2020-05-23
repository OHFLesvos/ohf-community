@extends('layouts.app')

@section('title', __('fundraising.create_donor'))

@section('content')
    <div id="fundraising-app">
        <donor-create-page>
            @lang('app.loading')
        </donor-create-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
