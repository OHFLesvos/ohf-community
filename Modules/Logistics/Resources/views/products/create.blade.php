@extends('layouts.app')

@section('title', __('logistics::products.register_product'))

@section('content')

    {!! Form::open(['route' => ['logistics.products.store'], 'method' => 'post']) !!}

       <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('name_translit', null, [ ], __('app.name_translit')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('category', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode($categories) ], __('app.category')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}
	
@endsection
