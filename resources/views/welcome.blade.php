@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    <p>
        <a href="{{ route('bank.index') }}" class="btn btn-default btn-lg"><i class="fa fa-bank"></i> <br>Bank</a> &nbsp;
        <a href="{{ route('people.index') }}" class="btn btn-default btn-lg"><i class="fa fa-group"></i> <br>People</a>
    </p>

@endsection