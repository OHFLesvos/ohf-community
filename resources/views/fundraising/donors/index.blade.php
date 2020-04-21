@extends('fundraising.layouts.donors-donations')

@section('title', __('fundraising.donation_management'))

@section('wrapped-content')

    <div id="fundraising-app">
        <donors-table
            api-url="{{ route('api.fundraising.donors.index') }}"
            :tags='@json((object)$tags)'
        ></donors-table>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
