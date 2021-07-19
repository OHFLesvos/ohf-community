@extends('layouts.app')

@section('title', __('Role'))
@section('site-title', $role->name . ' - '.__('Role'))

@section('content')

    <h1 class="display-4 mb-4">{{ $role->name }}</h1>

    @if($role->administrators()->find(Auth::id()) != null)
        <x-alert type="info">
            {{ __('You are administrator of this role.') }}
        </x-alert>
    @endif

    <div class="row">
        <div class="col-md">

            {{-- Role Profile --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">{{ __('Role') }}</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>{{ __('Created') }}</strong></div>
                            <div class="col-sm">{{ $role->created_at }} <small class="text-muted pl-2">{{ $role->created_at->diffForHumans() }}</small></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>{{ __('Last updated') }}</strong></div>
                            <div class="col-sm">{{ $role->updated_at }} <small class="text-muted pl-2">{{ $role->updated_at->diffForHumans() }}</small></div>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Permissions --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    {{ __('Permissions') }}
                    <span class="badge badge-secondary">{{ $role->permissions->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @if(count($permissions) > 0)
                        @foreach($permissions as $title => $elements)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $title == null ? __('General') : $title }}<span class="d-inline d-md-none">:</span>
                                    </div>
                                    <div class="col-md">
                                        <ul class="list-unstyled">
                                            @foreach($elements as $item)
                                                <li class="ml-4 ml-md-0 mt-1 mt-md-0">{{ $item }}</li>
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
        <div class="col-md">

            {{-- Role administrators --}}
            @php
                $users = $role->administrators->sortBy('name')->paginate(50);
            @endphp
            @if($users->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        {{ __('Role Administrators') }}
                        <span class="badge badge-secondary">{{ $role->administrators->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($users->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($users as $user)
                                    <a class="list-group-item list-group-item-action" href="{{ route('users.show', $user) }}">
                                        {{ $user->name }}
                                        @if($user->isSuperAdmin())
                                            <strong>({{ __('Administrator') }})</strong>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><em>{{ __('No users assigned.') }}</em></li>
                            </ul>
                        @endif
                    </div>
                </div>
                {{ $users->links() }}
            @endif

            {{-- Users --}}
            @php
                $users = $role->users->sortBy('name')->paginate(50);
            @endphp
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    {{ __('Users') }}
                    <span class="badge badge-secondary">{{ $role->users->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($users as $user)
                                <a class="list-group-item list-group-item-action" href="{{ route('users.show', $user) }}">
                                    {{ $user->name }}
                                    @if($user->isSuperAdmin())
                                        <strong>({{ __('Administrator') }})</strong>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><em>{{ __('No users assigned.') }}</em></li>
                        </ul>
                    @endif
                </div>
            </div>
            {{ $users->links() }}

        </div>
    </div>
@endsection
