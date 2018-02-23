@extends('layouts.app')

@section('title', __('app.view_role'))

@section('content')

    <table class="table">
        <tbody>
            <tr><th>@lang('app.name')</th><td>{{ $role->name }}</td></tr>
            <tr><th>@lang('app.users')</th><td>{{ $role->users->count() }}</td></tr>
            <tr>
                <th>@lang('app.permissions')</th>
                <td>
                   @forelse ($role->permissions->sortBy('key') as $permission)
                        @if ( isset( $permissions[$permission->key] ) )
                            {{ $permissions[$permission->key] }}<br>
                        @endif
                    @empty
                        <em>@lang('app.no_permissions')</em>
                    @endforelse
                </td>
            </tr>
            <tr>
                <th>@lang('app.created')</th>
                <td>{{ $role->created_at }}</td>
            </tr>
            <tr>
                <th>@lang('app.last_updated')</th>
                <td>{{ $role->updated_at }}</td>
            </tr>
        </tbody>
    </table>

@endsection
