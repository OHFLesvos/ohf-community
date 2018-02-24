<div class="card mb-4">
    <div class="card-header">
        @lang('app.users')
        <a class="pull-right" href="{{ route('users.index')  }}">@lang('app.manage')</a>
    </div>
    <div class="card-body">
        <p>
            @lang('app.users_in_db', [ 'num_users' => $num_users ])<br>
            @lang('app.newest_user_is', [ 'link' => route('users.show', $latest_user), 'name' => $latest_user->name ])
    </div>
</div>
