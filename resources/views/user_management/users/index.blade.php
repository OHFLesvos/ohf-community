@extends('user_management.layouts.user-roles')

@section('title', __('app.users_and_roles'))

@section('wrapped-content')

    @if( ! $users->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th colspan="2">
                            @lang('app.name')
                            @unless($sort == 'name' && $order == 'asc')<a href="{{ route('users.index', ['sort' => 'name', 'order' => 'asc']) }}">@endunless @icon(sort-down)@unless($sort == 'name' && $order == 'asc')</a>@endunless
                            @unless($sort == 'name' && $order == 'desc')<a href="{{ route('users.index', ['sort' => 'name', 'order' => 'desc']) }}">@endunless @icon(sort-up)@unless($sort == 'name' && $order == 'desc')</a>@endunless
                        </th>
                        <th class="d-none d-sm-table-cell">@lang('app.email')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.roles')</th>
                        <th class="d-none d-md-table-cell text-center fit">@lang('userprofile.2FA')</th>
                        <th class="d-none d-md-table-cell fit">
                            @lang('app.registered')
                            @unless($sort == 'created_at' && $order == 'asc')<a href="{{ route('users.index', ['sort' => 'created_at', 'order' => 'asc']) }}">@endunless @icon(sort-down)@unless($sort == 'name' && $order == 'asc')</a>@endunless
                            @unless($sort == 'created_at' && $order == 'desc')<a href="{{ route('users.index', ['sort' => 'created_at', 'order' => 'desc']) }}">@endunless @icon(sort-up)@unless($sort == 'name' && $order == 'desc')</a>@endunless
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="fit align-middle">
                                <img src="{{ $user->avatarUrl('site_header') }}" alt="Gravatar" style="width: 30px; height: 30px;">
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('users.show', $user) }}" title="View user">{{ $user->name }}</a>
                                @if($user->isSuperAdmin())
                                    <strong>(@lang('app.administrator'))</strong>
                                @endif
                            </td>
                            <td class="d-none d-sm-table-cell align-middle">
                                <a href="mailto:{{ $user->email }}" title="Send e-mail">{{ $user->email }}</a>
                            </td>
                            <td class="d-none d-sm-table-cell align-middle">
                                @foreach ($user->roles->sortBy('name') as $role)
                                    <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>@if (! $loop->last), @endif
                                @endforeach
                            </td>
                            <td class="d-none d-md-table-cell text-center fit align-middle">
                                @empty($user->tfa_secret)
                                    @icon(times)
                                @else
                                    <span class="text-success">@icon(check)</span>
                                @endempty
                            </td>
                            <td class="d-none d-md-table-cell fit align-middle">
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
