@extends('layouts.app')

@section('title', __('app.view_role'))

@section('content')
    <div class="row">
        <div class="col-md-9">
            {{-- <div class="card">
                <div class="card-header">@lang('app.role')</div>
                <div class="card-body p-0"> --}}
                    <ul class="list-group list-group-flush mb-2">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.name')</strong></div>
                                <div class="col-sm">{{ $role->name }}</div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.users')</strong></div>
                                <div class="col-sm">{{ $role->users->count() }}</div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.permissions')</strong></div>
                                <div class="col-sm">
                                @forelse ($role->permissions->sortBy('key') as $permission)
                                    @if ( isset( $permissions[$permission->key] ) )
                                        @lang('permissions.' . $permission->key)<br>
                                    @endif
                                    @empty
                                        <em>@lang('app.no_permissions')</em>
                                    @endforelse
                                </div>
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
                {{-- </div>
            </div> --}}
        </div>
        @php
            $users = $role->users->sortBy('name')->paginate();
        @endphp
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">@lang('app.users')</div>
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
    </div>
@endsection
