@extends('layouts.app')

@section('title', __('app.view_role'))

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">@lang('app.role')</div>
                <div class="card-body p-0">
                    <table class="table m-0">
                        <tbody>
                            <tr><th>@lang('app.name')</th><td>{{ $role->name }}</td></tr>
                            <tr><th>@lang('app.users')</th><td>{{ $role->users->count() }}</td></tr>
                            <tr>
                                <th>@lang('app.permissions')</th>
                                <td>
                                @forelse ($role->permissions->sortBy('key') as $permission)
                                    @if ( isset( $permissions[$permission->key] ) )
                                        {{ $permissions[$permission->key] }}<br>
                                    @endif
                                    @empty
                                        <em>@lang('app.no_permissions')</em>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th>@lang('app.created')</th>
                                <td>{{ $role->created_at }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.last_updated')</th>
                                <td>{{ $role->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @php
            $users = $role->users->sortBy('name')->paginate();
        @endphp
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">@lang('app.users')</div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="list-group">
                            @foreach($users as $user)
                                <a class="list-group-item" href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
                            @endforeach
                        </div>
                    @else
                        <ul class="list-group">
                            <li class="list-group-item"><em>@lang('app.no_users_found')</em></li>
                        </ul>
                    @endif
                </div>
            </div>
            {{ $users->links() }}
        </div>
    </div>
@endsection
