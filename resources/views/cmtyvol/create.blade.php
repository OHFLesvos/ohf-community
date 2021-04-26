@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Register Community Volunteer'))

@section('content')
    {!! Form::open(['route' => ['cmtyvol.store'], 'files' => true]) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('General')</div>
            <div class="card-body columns-2">
                @include('cmtyvol.include.form')
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('Register')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
