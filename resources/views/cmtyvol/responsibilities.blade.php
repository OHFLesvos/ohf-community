@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.responsibilities'))

@section('content')
    {!! Form::model($cmtyvol, ['route' => ['cmtyvol.updateResponsibilities', $cmtyvol], 'method' => 'put']) !!}
        <div class="card mb-4">
            <div class="card-header">@lang('people.occupation')</div>
            {{ Form::bsListWithDateRange('responsibilities', $responsibilities, $value, __('app.responsibilities')) }}
        </div>
        <p class="text-right">
            <x-form.bs-submit-button :label="__('Apply changes')"/>
        </p>
    {!! Form::close() !!}
@endsection
