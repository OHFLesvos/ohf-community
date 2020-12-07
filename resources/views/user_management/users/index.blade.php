@extends('user_management.layouts.user-roles')

@section('title', __('app.users_and_roles'))

@section('wrapped-content')
    <div id="user-management-app">
        <user-index-page>
            @lang('app.loading')
        </user-index-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/user_management.js') }}"></script>
@endpush
