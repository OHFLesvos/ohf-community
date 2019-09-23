@extends('layouts.app')

@section('title', __('school::classes.classes'))

@section('content')

    @foreach($schoolClassCategories as $category)
        <h3>{{ $category['label'] }}</h3>
        @if( ! $category['classes']->isEmpty() )
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="">@lang('app.name')</th>
                            <th class="">@lang('school::classes.teacher')</th>
                            <th class="fit">@lang('app.start_date')</th>
                            <th class="fit">@lang('app.end_date')</th>
                            <th class="text-right fit">@lang('school::classes.room')</th>
                            <th class="text-right fit">@lang('school::students.students')</th>
                            <th class="text-right fit">@lang('app.capacity')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category['classes'] as $schoolClass)
                            <tr>
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
                                <td class="fit">{{ $schoolClass->start_date->toDateString() }}</td>
                                <td class="fit">{{ $schoolClass->end_date->toDateString() }}</td>
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
