@extends('layouts.app')

@section('title', __('app.permissions'))

@section('content')
    <div class="row justify-content-between">
        <div class="col-md">
            <h2 class="display-4">@lang('app.roles')</h2>
            <table class="table table-sm mb-4">
                <thead>
                    <tr>
                        <th>@lang('app.permission')</th>
                        <th>@lang('app.roles')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $k => $permission)
                        <tr>
                            <td>{{ $permission }}</td>
                            @php
                                $roles = App\RolePermission::where('key', $k)
                                    ->get()
                                    ->map(function($e){
                                        return $e->role;
                                    })
                                    ->sortBy('name');
                            @endphp
                            <td>
                                @foreach($roles as $role)
                                    <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a><br>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md">
            <h2 class="display-4">@lang('app.users')</h2>
            <table class="table table-sm mb-4">
                <thead>
                    <tr>
                        <th>@lang('app.permission')</th>
                        <th>@lang('app.users')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $k => $permission)
                        <tr>
                            <td>{{ $permission }}</td>
                            @php
                                $roles = App\RolePermission::where('key', $k)
                                    ->get()
                                    ->map(function($e){
                                        return $e->role;
                                    })
                                    ->sortBy('name');
                                $users = $roles->flatMap(function($e){
                                    return $e->users;
                                })
                                ->unique('id')
                                ->sortBy('name');
                            @endphp
                            <td>
                                @foreach($users as $user)
                                    <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a><br>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
