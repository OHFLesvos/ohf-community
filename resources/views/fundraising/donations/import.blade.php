@extends('layouts.app')

@section('title', __('app.import'))

@section('content')
    <div id="fundraising-app">
        <donations-import-page>
            @lang('app.loading')
        </donations-import-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection

