@extends('layouts.app')

@section('title', __('library.library'))

@section('content')
    <div id="library-app">
        <library-app>
            @lang('app.loading')
        </library-app>
    </div>
@endsection

@push('footer')
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
    <script src="{{ mix('js/library.js') }}"></script>
@endpush
