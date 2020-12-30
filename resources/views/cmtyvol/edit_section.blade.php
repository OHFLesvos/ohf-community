@extends('layouts.app', ['wide_layout' => false])

@section('title', __('cmtyvol.edit'))

@section('content')
    {!! Form::model($cmtyvol, ['route' => ['cmtyvol.update', $cmtyvol, 'section' => $section], 'method' => 'put', 'files' => true]) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">{{ $section_label }}</div>
            <div class="card-body columns-2">
                @include('cmtyvol.include.form')
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('app.update')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
