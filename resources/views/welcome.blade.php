@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    <p><a href="{{ route('bank.index') }}" class="btn btn-primary"><i class="fa fa-bank"></i> Bank</a></p>

@endsection