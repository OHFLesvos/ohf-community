@extends('layouts.app')

@section('title', __('cmtyvol.edit') . ' : ' . $section_label)

@section('content')

    {!! Form::model($cmtyvol, ['route' => ['cmtyvol.update', $cmtyvol, 'section' => $section], 'method' => 'put', 'files' => true]) !!}

        <div class="columns-2 mb-4">
            @include('cmtyvol.form')
        </div>

        <p>
            <x-form.bs-submit-button :label="__('app.update')"/>
        </p>

    {!! Form::close() !!}

@endsection
