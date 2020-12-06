@extends('layouts.app')

@section('title', __('cmtyvol.register'))

@section('content')

    {!! Form::open(['route' => ['cmtyvol.store'], 'files' => true]) !!}

        <div class="columns-2 mb-4">
            @include('cmtyvol.form')
        </div>

        <p>
            <x-form.bs-submit-button :label="__('app.register')"/>
        </p>

    {!! Form::close() !!}

@endsection
