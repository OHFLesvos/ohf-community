@extends('layouts.app')

@section('title', __('school::students.students'))

@section('content')

    <div id="school-app">

        <h3>{{ $class->name }}</h3>
        <p>
            <strong>@lang('school::classes.teacher'):</strong> {{ $class->teacher_name }}<br>
            <strong>@lang('app.period'):</strong> {{ $class->start_date->toDateString() }} - {{ $class->end_date->toDateString() }}<br>
            <strong>@lang('school::classes.room'):</strong> {{ $class->room_name }}<br>
            <strong>@lang('app.capacity'):</strong> {{ $class->students()->count() }} / {{ $class->capacity }}
        </p>

        <school-class-register-student 
            filter-persons-url="{{ route('people.filterPersons') }}"
            add-student-url="{{ route('school.classes.students.add', $class) }}"
            redirect-url="{{ route('school.classes.students.index', $class) }}"
        >
        </school-class-register-student>

        @if( ! $class->students->isEmpty() )
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="">@lang('app.name')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($class->students->sortBy('family_name') as $student)
                            <tr>
                                <td>
                                    @can('view', $student)
                                        <a href="{{ route('school.classes.students.show', [$class, $student]) }}">
                                    @endcan
                                    @include('people::person-label', ['person'=> $student])
                                    @can('view', $student)
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @component('components.alert.info')
                @lang('school::students.no_students_registered_in_class')
            @endcomponent
        @endif

    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/school.js') }}?v={{ $app_version }}"></script>
@endsection