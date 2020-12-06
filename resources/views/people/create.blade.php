@extends('layouts.app')

@section('title', __('people.register_person'))

@section('content')

    {!! Form::open(['route' => 'people.store']) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('people.name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('family_name', null, [ 'required' ], __('people.family_name')) }}
            </div>
            <div class="col-md-auto">
                {{ Form::genderSelect('gender', null, __('people.gender')) }}
            </div>
            <div class="col-md-auto">
                {{ Form::bsStringDate('date_of_birth', null, [ ], __('people.date_of_birth')) }}
            </div>
            <div class="col-md-auto">
                <p>@lang('people.age')</p>
                <span id="age">?</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsNumber('police_no', null, [ 'prepend' => '05/' ], __('people.police_number'), __('people.leading_zeros_added_automatically')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('nationality', null, [ 'list' => $countries ], __('people.nationality')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('languages_string', null, [], __('people.languages')) }}
            </div>
        </div>
        {{ Form::bsText('remarks', null, [], __('people.remarks')) }}

        <p>
            <x-form.bs-submit-button :label="__('app.register')"/>
        </p>

    {!! Form::close() !!}

@endsection

