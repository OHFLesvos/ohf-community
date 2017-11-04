@extends('layouts.app')

@section('title', 'View User')

@section('content')

    @if ( $user == Auth::user() )
        @component('components.alert.info')
            This is your own user account.
        @endcomponent
    @endif

    <table class="table">
        <tbody>
            <tr><th>Name</th><td>{{ $user->name }}</td></tr>
            <tr>
                <th>E-Mail</th>
                <td>
                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                </td>
            </tr>
            <tr>
                <th>Roles</th>
                <td>
                    @forelse ($user->roles->sortBy('name') as $role)
                        {{ $role->name }}<br>
                    @empty
                        <em>No roles</em>
                    @endforelse
                </td>
            </tr>
            <tr>
                <th>Administrator</th>
                <td>
                    @if ( $user->isSuperAdmin() )
                        @icon(check text-success)
                    @else
                        @icon(times)
                    @endif
                </td>
            </tr>
            <tr><th>Registered</th><td>{{ $user->created_at }}</td></tr>
            <tr><th>Last updated</th><td>{{ $user->updated_at }}</td></tr>
        </tbody>
    </table>

@endsection
