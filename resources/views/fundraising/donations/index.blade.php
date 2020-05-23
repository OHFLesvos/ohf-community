@extends('fundraising.layouts.donors-donations')

@section('title', __('fundraising.donation_management'))

@section('wrapped-content')
    <div id="fundraising-app">
        <donations-index-page>
            @lang('app.loading')
        </donations-index-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
