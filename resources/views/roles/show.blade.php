@extends('layouts.app')

@section('title', __('app.view_role'))

@section('content')

    <ul class="list-group list-group-flush mb-4">
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.name')</strong></div>
                <div class="col-sm">{{ $role->name }}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.created')</strong></div>
                <div class="col-sm">{{ $role->created_at }} <small class="text-muted pl-2">{{ $role->created_at->diffForHumans() }}</small></div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.last_updated')</strong></div>
                <div class="col-sm">{{ $role->updated_at }} <small class="text-muted pl-2">{{ $role->updated_at->diffForHumans() }}</small></div>
            </div>
        </li>
    </ul>

    <div class="row">

        {{-- Users --}}
        @php
            $users = $role->users->sortBy('name')->paginate();
        @endphp
        <div class="col-md">
            <div class="card mb-4">
                <div class="card-header">@lang('app.users') ({{ $role->users->count() }})</div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($users as $user)
                                <a class="list-group-item" href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
                            @endforeach
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><em>@lang('app.no_users_assigned')</em></li>
                        </ul>
                    @endif
                </div>
            </div>
            {{ $users->links() }}
        </div>

        {{-- Permissions --}}
        <div class="col-md">
            <div class="card mb-4">
                <div class="card-header">@lang('app.permissions') ({{ $role->permissions->count() }})</div>
                <ul class="list-group list-group-flush">
                    @if(count($permissions) > 0)
                        @foreach($permissions as $title => $elements)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $title == null ? __('app.general') : $title }}<span class="d-inline d-md-none">:</span>
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
                        <li class="list-group-item"><em>@lang('app.no_permissions_assigned')</em></li>
                    @endif
                </ul>
            </div>
        </div>

    </div>
@endsection
