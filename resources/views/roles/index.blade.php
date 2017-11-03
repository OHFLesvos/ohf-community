@extends('layouts.app')

@section('title', 'Roles')

@section('buttons')
    @can('create', App\Role::class)
        {{ Form::bsButtonLink(route('roles.create'), 'Add', 'plus-circle', 'primary') }}
    @endcan
@endsection

@section('content')

    @if( ! $roles->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover table-responsive-md">
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
        @component('components.alert.info')
            No roles found.
        @endcomponent
	@endif
	
@endsection
