@extends('layouts.app')

@section('title', __('accounting.edit_transaction'))

@section('content')
    {!! Form::model($transaction, ['route' => ['accounting.transactions.update', $transaction], 'method' => 'put', 'files' => true]) !!}
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsDate('date', Carbon\Carbon::today(), [ 'required', 'autofocus' ], __('app.date')) }}
            </div>
            <div class="col-sm-auto pb-3">
            {{ Form::bsRadioInlineList('type', [ 'income' => __('accounting.income'), 'spending' => __('accounting.spending') ], 'spending', __('app.type')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('amount', null, [ 'required', 'step' => 'any', 'min' => 0], __('app.amount'), __('fundraising.write_decimal_point_as_comma')) }}
            </div>
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', null, [ 'min' => 1 ], __('accounting.receipt') . ' #') }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('beneficiary', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($beneficiaries)) ], __('accounting.beneficiary')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-4">
                {{ Form::bsText('project', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($projects)) ], __('app.project')) }}
            </div>
            <div class="col-sm-8">
                {{ Form::bsText('description', null, [  'required' ], __('app.description')) }}
            </div>
        </div>
        {{ Form::bsFile('receipt_picture', [], __('accounting.choose_picture_of_receipt')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>
    {!! Form::close() !!}
@endsection
