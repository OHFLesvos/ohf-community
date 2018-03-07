@extends('layouts.app')

@section('title', __('app.view_user'))

@section('content')

    @if ( $user == Auth::user() )
        @component('components.alert.info')
            @lang('app.this_is_your_own_account')
        @endcomponent
    @endif

    <div class="row">
        <div class="col-md-9">
            {{-- <div class="card mb-4">
                <div class="card-header">@lang('app.user')</div>
                <div class="card-body p-0"> --}}
                    <ul class="list-group list-group-flush mb-2">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.name')</strong></div>
                                <div class="col-sm">{{ $user->name }}</div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.email')</strong></div>
                                <div class="col-sm">
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.roles')</strong></div>
                                <div class="col-sm">
                                    @forelse ($user->roles->sortBy('name') as $role)
                                        <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a><br>
                                    @empty
                                        <em>@lang('app.no_roles')</em>
                                    @endforelse
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.registered')</strong></div>
                                <div class="col-sm">{{ $user->created_at }}</div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.last_updated')</strong></div>
                                <div class="col-sm">{{ $user->updated_at }}</div>
                            </div>
                        </li>
                    </ul>
                {{-- </div>
            </div> --}}
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">@lang('app.permissions')</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if ( $user->isSuperAdmin() )
                            <li class="list-group-item list-group-item-warning">
                                @lang('app.user_is_admin_has_all_permissions')
                            </li>
                        @endif
                        @if($user->permissions()->count() > 0)
                            @foreach($user->permissions() as $permission)
                                <li class="list-group-item">{{ $permissions[$permission->key] }}</li>
                            @endforeach
                        @else
                            <li class="list-group-item"><em>@lang('app.no_permissions')</em></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
