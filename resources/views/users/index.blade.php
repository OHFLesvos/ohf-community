@extends('layouts.app')

@section('title', 'Tasks')

@section('content')

	<h1 class="display-4">Users</h1>
	<br>

	@if( ! $users->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><a href="{{ route('users.show', $user) }}" title="Edit">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
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
