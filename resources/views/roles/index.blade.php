@extends('layouts.app')

@section('title', 'Roles')

@section('buttons')
    @can('create', App\Role::class)
        <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Role</a>
    @endcan
@endsection

@section('content')

    @if( ! $roles->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Users</th>
                    <th>Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td><a href="{{ route('roles.show', $role) }}" title="View Role">{{ $role->name }}</a></td>
                        <td>{{ $role->users->count()  }}</td>
                        <td>{{ $role->permissions->count()  }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $roles->links('vendor.pagination.bootstrap-4') }}
 
    @else
		<div class="alert alert-info">
            <i class="fa fa-info-circle"></i> No roles found.
        </div>
	@endif
	
@endsection
