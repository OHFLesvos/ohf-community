@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

	<h1 class="display-4">Welcome</h1>
	<p>Please select a module:</p>
    <p>
        <a href="{{ route('people.index') }}" class="btn btn-outline-primary btn-lg"><i class="fa fa-group"></i> <br>People</a> &nbsp;
        <a href="{{ route('bank.index') }}" class="btn btn-outline-primary btn-lg"><i class="fa fa-bank"></i> <br>Bank</a> &nbsp;
    </p>

@endsection