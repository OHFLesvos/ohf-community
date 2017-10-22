@extends('layouts.app')

@section('title', 'Users')

@section('content')

    <span class="pull-right">
        @can('create', App\User::class)
            <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> New user</a> &nbsp;
        @endcan
    </span>

	<h1 class="display-4">Users</h1>
	<br>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> {{ session('info') }}
        </div>
    @endif

	@if( ! $users->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Roles</th>
                    <th>Administrator</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><a href="{{ route('users.show', $user) }}" title="View user">{{ $user->name }}</a></td>
                        <td><a href="mailto:{{ $user->email }}" title="Send e-mail">{{ $user->email }}</a></td>
                        <td>
                            @foreach ($user->roles->sortBy('name') as $role)
                                {{ $role->name }}@if (! $loop->last), @endif
                            @endforeach
                        </td>
                        <td>
                            @if ( $user->isSuperAdmin() )
                                <i class="fa fa-check text-success"></i>
                            @endif
                        </td>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	@else
		<div class="alert alert-info">
            No users found.
        </div>
	@endif
	
@endsection
