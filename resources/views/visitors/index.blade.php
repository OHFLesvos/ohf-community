@extends('layouts.app')

@section('title', __('app.visitors'))

@section('content')
    <div id="visitors-app">
        <visitors-app>
            @lang('app.loading')
        </visitors-app>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/visitors.js') }}"></script>
@endpush
