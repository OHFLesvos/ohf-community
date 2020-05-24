@extends('layouts.app')

@section('title', __('fundraising.edit_donor'))

@section('content')
    <div id="fundraising-app">
        <donor-edit-page
            :id="{{ $donor->id }}"
        >
            @lang('app.loading')
        </donor-edit-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
