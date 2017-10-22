@extends('layouts.app')

@section('title', 'Users')

@section('content')

    <span class="pull-right">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit user</a> &nbsp;
        @if ( $user != Auth::user() )
            <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                {{ Form::button('<i class="fa fa-trash"></i> Delete user', [ 'type' => 'submit', 'class' => 'btn btn-danger', 'id' => 'delete_button' ]) }} &nbsp;
            </form>
        @endif
        <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Overview</a>
    </span>

    <h1 class="display-4">View User</h1>
	<br>

    @if ( $user == Auth::user() )
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> This is your own user account.
        </div>
    @endif

    <table class="table">
        <tbody>
            <tr><th>Name</th><td>{{ $user->name }}</td></tr>
            <tr><th>E-Mail</th><td>{{ $user->email }}</td></tr>
            <tr><th>Registered</th><td>{{ $user->created_at }}</td></tr>
            <tr><th>Last updated</th><td>{{ $user->updated_at }}</td></tr>
        </tbody>
    </table>

@endsection

@section('script')
    $( '#delete_button' ).on('click', function(){
        return confirm('Do you really want to delete this user?');
    });
@endsection