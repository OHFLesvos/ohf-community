@extends('layouts.app')

@section('title', __('accounting.edit_wallet'))

@section('content')

    {!! Form::model($wallet, ['route' => ['accounting.wallets.update', $wallet], 'method' => 'put']) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autocomplete' => 'off' ], __('app.name')) }}
            </div>
        </div>
        <div class="form-row mb-4">
            <div class="col-md">
                {{ Form::bsCheckbox('is_default', 1, null, __('app.default')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
