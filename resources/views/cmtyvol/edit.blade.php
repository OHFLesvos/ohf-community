@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.edit_community_volunteer'))

@section('content')
    {!! Form::model($cmtyvol, ['route' => ['cmtyvol.update', $cmtyvol], 'method' => 'put', 'files' => true]) !!}
        @foreach($data as $section => $fields)
            <div class="card shadow-sm mb-4">
                <div class="card-header">{{ $sections[$section] }}</div>
                <div class="card-body columns-2">
                    @include('cmtyvol.include.form')
                </div>
            </div>
        @endforeach
        <p class="text-right">
            <x-form.bs-submit-button :label="__('app.update')"/>
        </p>
    {!! Form::close() !!}
@endsection
