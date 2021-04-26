@extends('layouts.app')

@section('title', __('Report') . ': ' . __('Visitor check-ins'))

@section('content')
    <div id="reports-app">
        <reports-app>
            @lang('Loading...')
        </reports-app>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/reports.js') }}"></script>
@endpush
