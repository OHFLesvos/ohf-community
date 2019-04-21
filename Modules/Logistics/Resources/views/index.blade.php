@extends('layouts.app')

@section('title', __('logistics::logistics.logistics'))

@section('content')
    <h1>Hello World</h1>
    <p>
        This view is loaded from module: {!! config('logistics.name') !!}
    </p>
@stop
