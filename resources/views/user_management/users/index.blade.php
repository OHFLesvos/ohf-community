@extends('layouts.user-management')

@section('title', __('Users'))

@section('content')
    <div id="user-management-app">
        <user-index-page>
            @lang('Loading...')
        </user-index-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/user_management.js') }}"></script>
@endpush
