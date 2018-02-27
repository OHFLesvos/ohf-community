@extends('layouts.app')

@section('title', __('app.view_role'))

@section('content')
    <div class="row">
        <div class="col-md-9">
            <table class="table">
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
        <div class="col-md-3">
            <table class="table table-sm">
                <tbody>
                    @foreach($role->users as $user)
                        <tr>
                            <td><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
