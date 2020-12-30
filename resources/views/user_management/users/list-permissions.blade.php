@extends('layouts.app')

@section('title', __('app.user_permissions'))

@section('content')
    <div class="columns-3">
        @foreach($permissions as $title => $elements)
            <div class="mb-4 column-break-avoid">
                <h4>{{ $title == null ? __('app.general') : $title }}</h4>
                @foreach($elements as $key => $label)
                    <div class="mb-4 column-break-avoid">
                        <h6>{{ $label }}</h6>
                        @php
                            $roles = App\Models\RolePermission::where('key', $key)
                                ->get()
                                ->map(fn ($e) => $e->role)
                                ->sortBy('name');
                            $users = $roles->flatMap(fn ($e) => $e->users)
                                ->concat(App\Models\User::where('is_super_admin', true)->get())
                                ->unique('id')
                                ->sortBy('name');
                        @endphp
                        @forelse($users as $user)
                            <a href="{{ route('users.show', $user) }}">
                                {{ $user->name }}</a>
                                @if($user->isSuperAdmin())
                                    <small><x-icon icon="user-shield" :title="__('app.administrator')"/></small>
                                @endif
                            <br>
                        @empty
                            <em>@lang('app.no_users_assigned')</em>
                        @endforelse
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
