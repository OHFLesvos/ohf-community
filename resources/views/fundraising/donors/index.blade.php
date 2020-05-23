@extends('fundraising.layouts.donors-donations')

@section('title', __('fundraising.donation_management'))

@section('wrapped-content')
    <div id="fundraising-app">
        <donors-index-page
            @isset($tag) tag="{{ $tag }}" @endisset
        >
            @lang('app.loading')
        </donors-index-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
