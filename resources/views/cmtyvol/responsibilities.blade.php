@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Responsibilities'))

@section('content')
    {!! Form::model($cmtyvol, ['route' => ['cmtyvol.updateResponsibilities', $cmtyvol], 'method' => 'put']) !!}
        <div class="card mb-4">
            <div class="card-header">{{ __('Occupation') }}</div>
            {{ Form::bsListWithDateRange('responsibilities', $responsibilities, $value, __('Responsibilities')) }}
        </div>
        <p class="text-right">
            <x-form.bs-submit-button :label="__('Apply changes')"/>
        </p>
    {!! Form::close() !!}
@endsection
