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
                <div class="col-sm">{{ $role->created_at }}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.last_updated')</strong></div>
                <div class="col-sm">{{ $role->updated_at }}</div>
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
                            <li class="list-group-item"><em>@lang('app.no_users_found')</em></li>
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
                <div class="card-body p-0">
                    @if(count($permissions) > 0)
                        <ul class="my-3 mx-0">
                            @foreach($permissions as $title => $elements)
                                <li>{{ $title == null ? __('app.general') : $title }}:<ul>
                                @foreach($elements as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                                </ul>
                            @endforeach
                        </ul>
                    @else
                        <p class="mx-3 mt-3 mb-3"><em>@lang('app.no_permissions')</em></p>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
