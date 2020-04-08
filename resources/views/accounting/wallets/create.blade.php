@extends('layouts.app')

@section('title', __('accounting.create_wallet'))

@section('content')

    {!! Form::open(['route' => ['accounting.wallets.store']]) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
        </div>
        <div class="form-row mb-4">
            <div class="col-md">
                {{ Form::bsCheckbox('is_default', 1, null, __('app.default')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}

@endsection
