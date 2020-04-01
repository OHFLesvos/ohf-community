@extends('layouts.app')

@section('title', __('app.view_user'))

@section('content')

    @if ($user->id == Auth::id())
        @component('components.alert.info')
            @lang('app.this_is_your_own_account')
        @endcomponent
    @endif

    <div class="row">

        <div class="col-md">
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
                @isset($user->provider_name)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.oauth_provider')</strong></div>
                            <div class="col-sm">
                                {!! Form::open(['route' => ['users.disableOAuth', $user], 'method' => 'put']) !!}
                                    <p>{{ $user->provider_name }}</p>
                                    <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('@lang('userprofile.oauth_disable_for_user', [ 'name' => $user->name ])');">@icon(times) @lang('app.disable')</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </li>
                @endisset
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm"><strong>@lang('app.roles')</strong></div>
                        <div class="col-sm">
                            @forelse ($user->roles->sortBy('name') as $role)
                                <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>
                                <br>
                            @empty
                                <em>@lang('app.no_roles_assigned')</em>
                            @endforelse
                        </div>
                    </div>
                </li>
                @if($user->administeredRoles->count() > 0)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.role_administrator')</strong></div>
                            <div class="col-sm">
                                @forelse ($user->administeredRoles->sortBy('name') as $role)
                                    <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>
                                    <br>
                                @empty
                                    <em>@lang('app.no_roles_assigned')</em>
                                @endforelse
                            </div>
                        </div>
                    </li>
                @endif
                @isset($user->tfa_secret)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('userprofile.tfa_authentication')</strong></div>
                            <div class="col-sm">
                                {!! Form::open(['route' => ['users.disable2FA', $user], 'method' => 'put']) !!}
                                <p>@lang('userprofile.tfa_authentication_enabled').</p>
                                <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('@lang('userprofile.tfa_disable_for_user', [ 'name' => $user->name ])');">@icon(times) @lang('app.disable')</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </li>
                @endisset
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm"><strong>@lang('app.registered')</strong></div>
                        <div class="col-sm">{{ $user->created_at }} <small class="text-muted pl-2">{{ $user->created_at->diffForHumans() }}</small></div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm"><strong>@lang('app.last_updated')</strong></div>
                        <div class="col-sm">{{ $user->updated_at }} <small class="text-muted pl-2">{{ $user->updated_at->diffForHumans() }}</small></div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">@lang('app.permissions') ({{ $user->permissions()->count() }})</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if ($user->isSuperAdmin())
                            <li class="list-group-item list-group-item-warning">
                                @lang('app.user_is_admin_has_all_permissions')
                            </li>
                        @endif
                        @if(count($permissions) > 0)
                            @foreach($permissions as $title => $elements)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            {{ $title == null ? __('app.general') : $title }}<span class="d-inline d-md-none">:</span>
                                        </div>
                                        <div class="col-lg">
                                            <ul class="list-unstyled">
                                                @foreach($elements as $item)
                                                    <li class="ml-4 ml-lg-0 mt-1 mt-lg-0">{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item"><em>@lang('app.no_permissions_assigned')</em></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
