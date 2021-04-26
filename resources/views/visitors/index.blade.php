@extends('layouts.app')

@section('title', __('Visitors'))

@section('content')
    <div id="visitors-app">
        <visitors-app>
            @lang('Loading...')
        </visitors-app>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/visitors.js') }}"></script>
@endpush
