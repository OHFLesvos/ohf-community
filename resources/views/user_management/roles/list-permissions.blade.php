@extends('layouts.app')

@section('title', __('Role Permissions'))

@section('content')
    <div class="columns-3">
        @foreach($permissions as $title => $elements)
            <div class="mb-4 column-break-avoid">
                <h4>{{ $title == null ? __('General') : $title }}</h4>
                @foreach($elements as $key => $label)
                    <div class="mb-4 column-break-avoid">
                        <h6>{{ $label }}</h6>
                        @php
                            $roles = App\Models\RolePermission::where('key', $key)
                                ->get()
                                ->map(fn ($e) => $e->role)
                                ->sortBy('name');
                        @endphp
                        @forelse($roles as $role)
                            <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a><br>
                        @empty
                            <em>{{ __('No roles assigned.') }}</em>
                        @endforelse
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
