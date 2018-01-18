@extends('layouts.app')

@section('title', 'Reporting')

@section('content')

    <p>Available reports:</p>
    <div class="list-group">
        <a href="{{ route('reporting.people') }}" class="list-group-item list-group-item-action">@icon(users) People</a>
    </div>

@endsection
