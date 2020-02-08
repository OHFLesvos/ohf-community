@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <div id='tasks-app'>
        <task-list></task-list>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/tasks.js') }}?v={{ $app_version }}"></script>
@endsection
