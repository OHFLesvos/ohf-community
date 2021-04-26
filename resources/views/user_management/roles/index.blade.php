@extends('layouts.user-management')

@section('title', __('Roles'))

@section('content')
    @if(! $roles->isEmpty())
        <table class="table table-hover bg-white">
            <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th class="text-right fit">@lang('Users')</th>
                    <th class="text-right fit d-none d-sm-table-cell">@lang('Role Administrators')</th>
                    <th class="text-right fit d-none d-sm-table-cell">@lang('Permissions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>
                            <a href="{{ route('roles.show', $role) }}" title="View Role">{{ $role->name }}</a>
                            @if($role->administrators()->find(Auth::id()) != null)
                                <strong>(@lang('you are administrator'))</strong>
                            @endif
                        </td>
                        <td class="text-right fit">{{ $role->users->count() }}</td>
                        <td class="text-right fit d-none d-sm-table-cell">{{ $role->administrators->count() }}</td>
                        <td class="text-right fit d-none d-sm-table-cell">{{ $role->permissions->count()  }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $roles->links() }}
    @else
        <x-alert type="info">
            @lang('No roles found.')
        </x-alert>
    @endif
@endsection
