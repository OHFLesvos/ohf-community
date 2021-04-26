@extends('layouts.app')

@section('title', __('Donation Management'))

@section('content')
    <div id="fundraising-app">
        <fundraising-app>
            @lang('Loading...')
        </fundraising-app>
    </div>
@endsection

@push('footer')
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
    <script src="{{ mix('js/fundraising.js') }}"></script>
@endpush
