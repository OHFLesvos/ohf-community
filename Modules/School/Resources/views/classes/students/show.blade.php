@extends('layouts.app')

@section('title', __('school::students.student'))

@section('content')

    <h3>{{ $student->fullName }}</h3>
    <p>
        <strong>@lang('school::classes.class'):</strong> {{ $class->name }}<br>
        <strong>@lang('school::classes.teacher'):</strong> {{ $class->teacher_name }}<br>
        <strong>@lang('app.period'):</strong> {{ $class->start_date->toDateString() }} - {{ $class->end_date->toDateString() }}<br>
    </p>

@endsection
