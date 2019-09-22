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
                            <th class="">@lang('app.start_date')</th>
                            <th class="">@lang('app.end_date')</th>
                            <th class="">@lang('school::classes.teacher')</th>
                            {{-- <th class="">@lang('school::students.students')</th> --}}
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
                                <td>{{ $schoolClass->start_date->toDateString() }}</td>
                                <td>{{ $schoolClass->end_date->toDateString() }}</td>
                                <td>{{ $schoolClass->teacher_name }}</td>
                                {{-- <td>{{ $schoolClass->students->count() }}</td> --}}
                                <td>{{ $schoolClass->capacity }}</td>
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
