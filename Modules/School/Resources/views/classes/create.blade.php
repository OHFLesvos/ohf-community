@extends('layouts.app')

@section('title', __('school::classes.create_class'))

@section('content')

    {!! Form::open(['route' => ['school.classes.store'], 'method' => 'store']) !!}

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
                {{ Form::bsDate('start_date', null, [ 'required' ], __('app.start_date')) }}
            </div>
            <div class="col-md">
                {{ Form::bsDate('end_date', null, [ 'required' ], __('app.end_date')) }}
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
        {{ Form::bsText('remarks', null, [  ], __('app.remarks')) }}

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}
	
@endsection
