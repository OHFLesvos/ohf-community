@extends('layouts.app')

@section('title', __('app.role_permissions'))

@section('content')
    <div class="columns-3">
        @foreach($permissions as $title => $elements)
            <div class="mb-4 column-break-avoid">
                <h4>{{ $title == null ? __('app.general') : $title }}</h4>
                @foreach($elements as $key => $label)
                    <div class="mb-4 column-break-avoid">
                        <p class="mb-1">{{ $label }}:</p>
                        @php
                            $roles = App\Models\RolePermission::where('key', $key)
                                ->get()
                                ->map(fn ($e) => $e->role)
                                ->sortBy('name');
                        @endphp
                        @forelse($roles as $role)
                            <a class="pl-3" href="{{ route('roles.show', $role) }}">{{ $role->name }}</a><br>
                        @empty
                            <em class="pl-3">@lang('app.no_roles_assigned')</em>
                        @endforelse
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
