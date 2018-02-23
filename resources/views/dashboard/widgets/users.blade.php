@can('list', App\User::class)
    <div class="card mb-4">
        <div class="card-header">
            @lang('app.users')
            <a class="pull-right" href="{{ route('users.index')  }}">@lang('app.manage')</a>
        </div>
        <div class="card-body">
            <p>There are <strong>{{ $num_users }}</strong> users in our database. The newest user is <a href="{{ route('users.show', $latest_user) }}">{{ $latest_user->name }}</a>.</p>
        </div>
    </div>
@endcan