@extends('widgets.base', [
    'icon' => 'user-friends',
    'title' => __('Users'),
    'href' => route('users.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Users in the database') => $num_users,

        ],
    ])
    <div class="card-body p-3 border-top">
        {{ __('The newest user is <a href=":href">:name</a>, registered :registered.', [
            'href' => route('users.show', $latest_user),
            'name' => $latest_user->name,
            'registered' => $latest_user->created_at->diffForHumans(),
        ]) }}
    </div>
@endsection
