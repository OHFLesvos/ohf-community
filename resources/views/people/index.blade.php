@extends('layouts.app')

@section('title', __('people.people'))

@section('content')

    <div id="people-app">
        <people-table
            api-url="{{ route('api.people.index') }}"
        ></people-table>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection
