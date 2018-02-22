@extends('layouts.app')

@section('title', __('app.view_user'))

@section('content')

    @if ( $user == Auth::user() )
        @component('components.alert.info')
            @lang('app.this_is_your_own_account')
        @endcomponent
    @endif

    <table class="table">
        <tbody>
            <tr>
                <th>@lang('app.name')</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>@lang('app.email')</th>
                <td>
                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                </td>
            </tr>
            <tr>
                <th>@lang('app.roles')</th>
                <td>
                    @forelse ($user->roles->sortBy('name') as $role)
                        {{ $role->name }}<br>
                    @empty
                        <em>@lang('app.no_roles')</em>
                    @endforelse
                </td>
            </tr>
            <tr>
                <th>@lang('app.administrator')</th>
                <td>
                    @if ( $user->isSuperAdmin() )
                        @icon(check text-success)
                    @else
                        @icon(times)
                    @endif
                </td>
            </tr>
            <tr>
                <th>@lang('app.registered')</th>
                <td>{{ $user->created_at }}</td>
            </tr>
            <tr>
                <th>@lang('app.last_updated')</th>
                <td>{{ $user->updated_at }}</td>
            </tr>
        </tbody>
    </table>

@endsection
