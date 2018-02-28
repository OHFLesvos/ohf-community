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
            <div class="card">
                <div class="card-header">@lang('app.user')</div>
                <div class="card-body p-0">
                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <th>@lang('app.name')</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.email')</th>
                                <td>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>@lang('app.roles')</th>
                                <td>
                                    @forelse ($user->roles->sortBy('name') as $role)
                                        <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a><br>
                                    @empty
                                        <em>@lang('app.no_roles')</em>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th>@lang('app.administrator')</th>
                                <td>
                                    @if ( $user->isSuperAdmin() )
                                        @icon(check text-success)
                                    @else
                                        @icon(times)
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>@lang('app.registered')</th>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.last_updated')</th>
                                <td>{{ $user->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">@lang('app.permissions')</div>
                <div class="card-body p-0">
                    <ul class="list-group">
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
