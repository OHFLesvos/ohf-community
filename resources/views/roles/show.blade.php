@extends('layouts.app')

@section('title', 'View Role')

@section('buttons')
    @can('update', $role)
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
    @endcan
    @can('delete', $role)
        <form method="POST" action="{{ route('roles.destroy', $role) }}" class="d-inline">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            {{ Form::button('<i class="fa fa-trash"></i> Delete', [ 'type' => 'submit', 'class' => 'btn btn-danger', 'id' => 'delete_button' ]) }}
        </form>
    @endcan
    <a href="{{ route('roles.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Close</a>
@endsection

@section('content')

    <table class="table">
        <tbody>
            <tr><th>Name</th><td>{{ $role->name }}</td></tr>
            <tr><th>Users</th><td>{{ $role->users->count() }}</td></tr>
            <tr>
                <th>Permissions</th>
                <td>
                   @forelse ($role->permissions->sortBy('key') as $permission)
                        @if ( isset( $permissions[$permission->key] ) )
                            {{ $permissions[$permission->key] }}<br>
                        @endif
                    @empty
                        <em>No permissions</em>
                    @endforelse
                </td>
            </tr>
            <tr><th>Created</th><td>{{ $role->created_at }}</td></tr>
            <tr><th>Last updated</th><td>{{ $role->updated_at }}</td></tr>
        </tbody>
    </table>

@endsection

@section('script')
    $( '#delete_button' ).on('click', function(){
        return confirm('Do you really want to delete this role?');
    });
@endsection
