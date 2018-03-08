@extends('layouts.user-roles')

@section('title', __('app.users'))

@section('wrapped-content')

    @if( ! $users->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.email')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.roles')</th>
                        <th class="d-none d-md-table-cell">@lang('app.registered')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('users.show', $user) }}" title="View user">{{ $user->name }}</a>
                                @if($user->isSuperAdmin())
                                    <strong>(@lang('app.administrator'))</strong>
                                @endif
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <a href="mailto:{{ $user->email }}" title="Send e-mail">{{ $user->email }}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                @foreach ($user->roles->sortBy('name') as $role)
                                    <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>@if (! $loop->last), @endif
                                @endforeach
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $user->created_at }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    @else
        @component('components.alert.info')
            @lang('app.no_users_found')
        @endcomponent
	@endif
	
@endsection
