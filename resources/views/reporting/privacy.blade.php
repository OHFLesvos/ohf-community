@extends('layouts.app')

@section('title', __('reporting.privacy'))

@section('content')
    <h4 class="mb-4">@lang('app.user_witzh_access_to_sensitive_data')</h4>
    @if($users->count() > 0)
        <ul class="list-group list-group-flush mb-2">
            @foreach($users as $user)
                <li class="list-group-item px-1">
                    <div class="row">
                        <div class="col-sm">
                            <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a><br>
                            @empty($user->tfa_secret)
                                <small class="text-danger">@lang('userprofile.2FA_inactive')</small>
                            @else
                                <small class="text-success">@lang('userprofile.2FA_active')</small>
                            @endempty
                            @if($user->isSuperAdmin())
                                <small class="text-info">@lang('app.administrator')</small>
                            @endif
                    </div>
                        <div class="col-sm mt-2 mt-sm-0">
                            <ul class="list-unstyled">
                                @if($user->isSuperAdmin())
                                    @foreach(collect($permissions)->filter(function($p) {
                                        return $p['sensitive'];
                                    }) as $key => $permission)
                                        <li class="mt-1 mt-md-0">@lang('permissions.' . $key)</li>
                                    @endforeach
                                @else
                                    @foreach($user->permissions()->filter(function($p) use($permissions) {
                                            return isset($permissions[$p->key]) && $permissions[$p->key]['sensitive'];
                                        }) as $permission)
                                        <li class="mt-1 mt-md-0">@lang('permissions.' . $permission->key)</li>
                                    @endforeach
                                @endif
                            </ul>
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
