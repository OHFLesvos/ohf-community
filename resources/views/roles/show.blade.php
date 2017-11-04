@extends('layouts.app')

@section('title', 'View Role')

@section('content')

    <table class="table">
        <tbody>
            <tr><th>Name</th><td>{{ $role->name }}</td></tr>
            <tr><th>Users</th><td>{{ $role->users->count() }}</td></tr>
            <tr>
                <th>Permissions</th>
                <td>
                   @forelse ($role->permissions->sortBy('key') as $permission)
                        @if ( isset( $permissions[$permission->key] ) )
                            {{ $permissions[$permission->key] }}<br>
                        @endif
                    @empty
                        <em>No permissions</em>
                    @endforelse
                </td>
            </tr>
            <tr><th>Created</th><td>{{ $role->created_at }}</td></tr>
            <tr><th>Last updated</th><td>{{ $role->updated_at }}</td></tr>
        </tbody>
    </table>

@endsection
