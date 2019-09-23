@extends('layouts.app')

@section('title', __('school::classes.edit_class'))

@section('content')

    {!! Form::model($class, ['route' => ['school.classes.update', $class], 'method' => 'put']) !!}

       <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required' ], __('app.name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('teacher_name', null, [ 'required' ], __('school::classes.teacher')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsDate('start_date', $class->start_date->toDateString(), [ 'required' ], __('app.start_date')) }}
            </div>
            <div class="col-md">
                {{ Form::bsDate('end_date', $class->end_date->toDateString(), [ 'required' ], __('app.end_date')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('room_name', null, [ 'required' ], __('school::classes.room')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('capacity', null, [ 'required' ], __('app.capacity')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}
	
@endsection
