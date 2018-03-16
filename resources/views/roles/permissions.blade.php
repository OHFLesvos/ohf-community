@extends('layouts.app')

@section('title', __('app.role_permissions'))

@section('content')
    <div class="columns-3">
        @foreach($permissions as $key => $permission)
            <div class="mb-4" style="-webkit-column-break-inside: avoid;
        -moz-column-break-inside:avoid;
        -moz-page-break-inside:avoid;
        page-break-inside: avoid;
        break-inside: avoid-column;">
                <h4>@lang('permissions.' . $key)</h4>
                @php
                    $roles = App\RolePermission::where('key', $key)
                        ->get()
                        ->map(function($e){
                            return $e->role;
                        })
                        ->sortBy('name');
                @endphp
                @forelse($roles as $role)
                    <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a><br>
                @empty
                    <em>@lang('app.no_roles')</em>
                @endforelse
            </div>
        @endforeach
    </div>
@endsection
