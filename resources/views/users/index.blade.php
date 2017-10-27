@extends('layouts.app')

@section('title', 'Users')

@section('buttons')
    @can('create', App\User::class)
        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add</a>
    @endcan
@endsection

@section('content')

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
        {{ $users->links('vendor.pagination.bootstrap-4') }}
 
    @else
		<div class="alert alert-info">
            <i class="fa fa-info-circle"></i> No users found.
        </div>
	@endif
	
@endsection
