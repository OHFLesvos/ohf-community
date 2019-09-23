@extends('layouts.app')

@section('title', __('school::students.student'))

@section('content')

    <h3>{{ $student->fullName }}</h3>
    <p>
        <strong>@lang('school::classes.class'):</strong> {{ $class->name }}<br>
        <strong>@lang('school::classes.teacher'):</strong> {{ $class->teacher_name }}<br>
        <strong>@lang('app.period'):</strong> {{ $class->start_date->toDateString() }} - {{ $class->end_date->toDateString() }}<br>
    </p>

    <h4>@lang('school::classes.classes')</h4>
    <table class="table table-sm table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>@lang('app.name')</th>
                <th>@lang('school::classes.teacher')</th>
                <th class="fit">@lang('app.start_date')</th>
                <th class="fit">@lang('app.end_date')</th>
                <th>@lang('app.remarks')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($student->schoolClasses->sortByDesc('end_date') as $studentsClass)
                <tr>
                    <td>
                        <a href="{{ route('school.classes.students.index', $studentsClass) }}">
                            {{ $studentsClass->name }}
                        </a>
                    </td>
                    <td>{{ $studentsClass->teacher_name }}</td>
                    <td class="fit">{{ $studentsClass->start_date->toDateString() }}</td>
                    <td class="fit">{{ $studentsClass->end_date->toDateString() }}</td>
                    <td>{{ $studentsClass->participation->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
