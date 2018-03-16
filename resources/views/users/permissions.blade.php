@extends('layouts.app')

@section('title', __('app.user_permissions'))

@section('content')
    <div class="columns-3">
        @foreach($permissions as $key => $permission)
            <div class="mb-4" style="-webkit-column-break-inside: avoid;
        -moz-column-break-inside:avoid;
        -moz-page-break-inside:avoid;
        page-break-inside: avoid;
        break-inside: avoid-column;">
                @php
                    $roles = App\RolePermission::where('key', $key)
                        ->get()
                        ->map(function($e){
                            return $e->role;
                        })
                        ->sortBy('name');
                    $users = $roles->flatMap(function($e){
                        return $e->users;
                    })
                    ->concat(App\User::where('is_super_admin', true)->get())
                    ->unique('id')
                    ->sortBy('name');
                @endphp
                <h4>@lang('permissions.' . $key)</h4>
                @forelse($users as $user)
                    <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
                    @if($user->isSuperAdmin())
                        (@lang('app.administrator'))
                    @endif
                    <br>
                @empty
                    <em>@lang('app.no_users')</em>
                @endforelse
            </div>
        @endforeach
    </div>
@endsection
