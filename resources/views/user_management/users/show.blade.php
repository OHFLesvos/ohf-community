@extends('layouts.app')

@section('title', __('User'))
@section('site-title', $user->name . ' - '.__('User'))

@section('content')

    <h1 class="display-4 mb-4">{{ $user->name }}</h1>

    @if ($user->id == Auth::id())
        <x-alert type="info">
            {{ __('This is your own user account.') }}
        </x-alert>
    @endif

    <div class="row">
        <div class="col-md-auto text-center mb-3">
            <x-user-avatar :user="$user" size="120"/>
        </div>

        <div class="col-md">
            <div class="card shadow-sm mb-4">
                <div class="card-header">{{ __('User Profile') }}</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>{{ __('E-Mail Address') }}</strong></div>
                            <div class="col-sm">
                                <x-links.email>{{ $user->email }}</x-links.email>
                            </div>
                        </div>
                    </li>
                    @isset($user->provider_name)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>{{ __('OAuth provider') }}</strong></div>
                                <div class="col-sm">
                                    {!! Form::open(['route' => ['users.disableOAuth', $user], 'method' => 'put']) !!}
                                        <p>{{ $user->provider_name }}</p>
                                        <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('{{ __('Do you really want to disable OAuth for :name?', [ 'name' => $user->name ]) }}');">
                                            <x-icon icon="unlink"/> {{ __('Remove') }}
                                        </button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </li>
                    @endisset
                    @isset($user->tfa_secret)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>{{ __('Two-Factor Authentication') }}</strong></div>
                                <div class="col-sm">
                                    {!! Form::open(['route' => ['users.disable2FA', $user], 'method' => 'put']) !!}
                                    <p>{{ __('Two-Factor Authentication is enabled.') }}.</p>
                                    <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('{{ __('Do you really want to disable Two-Factor Authentication for :name?', [ 'name' => $user->name ]) }}');">
                                        <x-icon icon="unlock"/> {{ __('Disable') }}
                                    </button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </li>
                    @endisset
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>{{ __('Registered') }}</strong></div>
                            <div class="col-sm">
                                {{ $user->created_at }}
                                <small class="text-muted pl-2">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>{{ __('Last updated') }}</strong></div>
                            <div class="col-sm">
                                {{ $user->updated_at }}
                                <small class="text-muted pl-2">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Roles --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    {{ __('Roles') }}
                    <span class="badge badge-secondary">{{ $user->roles()->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @if(count($user->roles) > 0)
                            @foreach($user->roles->sortBy('name') as $role)
                                <a href="{{ route('roles.show', $role) }}" class="list-group-item list-group-item-action">{{ $role->name }}</a>
                            @endforeach
                        @else
                            <li class="list-group-item"><em>{{ __('No roles assigned.') }}</em></li>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Administered roles --}}
            @if($user->administeredRoles->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        {{ __('Role Administrator') }}
                        <span class="badge badge-secondary">{{ $user->administeredRoles()->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($user->administeredRoles->sortBy('name') as $role)
                                <a href="{{ route('roles.show', $role) }}" class="list-group-item list-group-item-action">{{ $role->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-5">
            {{-- Permissions --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    {{ __('Permissions') }}
                    <span class="badge badge-secondary">{{ $user->permissions()->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if ($user->isSuperAdmin())
                            <li class="list-group-item list-group-item-warning">
                                {{ __('This user is an administrator and has therefore all permissions.') }}
                            </li>
                        @endif
                        @if(count($permissions) > 0)
                            @foreach($permissions as $title => $elements)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            {{ $title == null ? __('General') : $title }}<span class="d-inline d-md-none">:</span>
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
                            <li class="list-group-item"><em>{{ __('No permissions assigned.') }}</em></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
