@extends('user_management.layouts.user-roles')

@section('title', __('app.users_and_roles'))

@section('wrapped-content')
    <div id="user-management-app">
        <user-index-page>
            @lang('app.loading')
        </user-index-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/user_management.js') }}?v={{ $app_version }}"></script>
@endsection
