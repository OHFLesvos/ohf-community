@extends('layouts.app')

@section('title', __('reporting.user-access-sensitive-data'))

@section('content')
    @if($users->count() > 0)
        <ul class="list-group list-group-flush mb-2">
            @foreach($users as $user)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm">
                            <strong>{{ $user->name }}</strong><br>
                            <a href="{{ route('users.show', $user) }}">@lang('app.view')</a>
                        </div>
                        <div class="col-sm">
                            @foreach($user->permissions()->filter(function($p) use($permissions) {
                                    return isset($permissions[$p->key]) && $permissions[$p->key]['sensitive'];
                                }) as $permission)
                                @lang('permissions.' . $permission->key)<br>
                            @endforeach
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        @component('components.alert.info')
            @lang('app.no_users_found')
        @endcomponent
	@endif
@endsection
