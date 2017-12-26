@extends('layouts.app')

@section('title', 'Edit ' . $article->name . ' (' . $article->type . ')')

@section('content')
    {!! Form::model($article, ['route' => ['logistics.articles.update', $article], 'method' => 'put']) !!}
        {!! Form::bsText('name') !!}
        {!! Form::bsSelect('type', $types, null) !!}
        {!! Form::bsText('unit') !!}
        {!! Form::bsSubmitButton('Save') !!}
    {!! Form::close() !!}
@endsection
