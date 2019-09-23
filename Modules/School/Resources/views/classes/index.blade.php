@extends('layouts.app')

@section('title', __('school::classes.classes'))

@section('content')

    @foreach($schoolClassCategories as $category)
        <h2>{{ $category['label'] }}</h2>
        @if( ! $category['classes']->isEmpty() )
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="fit">@lang('app.start_date')</th>
                            <th class="fit">@lang('app.end_date')</th>
                            <th>@lang('app.name')</th>
                            <th>@lang('school::classes.teacher')</th>
                            <th>@lang('school::classes.room')</th>
                            <th class="text-right fit">@lang('school::students.students')</th>
                            <th class="text-right fit">@lang('app.capacity')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category['classes'] as $schoolClass)
                            <tr>
                                <td class="fit">{{ $schoolClass->start_date->toDateString() }}</td>
                                <td class="fit">{{ $schoolClass->end_date->toDateString() }}</td>
                                <td>
                                    @can('update', $schoolClass)
                                        <a href="{{ route('school.classes.edit', $schoolClass) }}">
                                    @endcan
                                        {{ $schoolClass->name }}
                                    @can('update', $schoolClass)
                                        </a>
                                    @endcan
                                </td>
                                <td>{{ $schoolClass->teacher_name }}</td>
                                <td>{{ $schoolClass->room_name }}</td>
                                <td class="text-right fit">
                                    @can('list', \Modules\School\Entities\Student::class)
                                        <a href="{{ route('school.classes.students.index', $schoolClass) }}">
                                    @endcan
                                        {{ $schoolClass->students()->count() }}
                                    @can('list', \Modules\School\Entities\Student::class)
                                        </a>
                                    @endcan
                                </td>
                                <td class="text-right fit">{{ $schoolClass->capacity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @component('components.alert.info')
                @lang('school::classes.no_classes_found')
            @endcomponent
        @endif
    @endforeach

@endsection
