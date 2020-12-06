@extends('layouts.app')

@section('title', __('people.people'))

@section('content')

    <div id="people-app">
        <people-table>
            @lang('app.loading')
        </people-table>
    </div>

@endsection

@push('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endpush
