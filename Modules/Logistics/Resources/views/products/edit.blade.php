@extends('layouts.app')

@section('title', __('logistics::products.edit_product'))

@section('content')

    {!! Form::model($product, ['route' => ['logistics.products.update', $product], 'method' => 'put']) !!}

       <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('name_local', null, [ ], __('app.name_local')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('category', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode($categories) ], __('app.category')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}
	
@endsection
