@extends('layouts.app')

@section('title', __('accounting::accounting.edit_transaction_number', ['number' => $transaction->receipt_no]))

@section('content')
    {!! Form::model($transaction, ['route' => ['accounting.transactions.update', $transaction], 'method' => 'put', 'files' => true]) !!}
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsDate('date', null, [ 'required', 'autofocus' ], __('app.date')) }}
            </div>
            <div class="col-sm-auto pb-3">
            {{ Form::bsRadioInlineList('type', [ 'income' => __('accounting::accounting.income'), 'spending' => __('accounting::accounting.spending') ], 'spending', __('app.type')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('amount', null, [ 'required', 'step' => 'any', 'min' => 0], __('app.amount'), __('app.write_decimal_point_as_comma')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('beneficiary', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($beneficiaries)) ], __('accounting::accounting.beneficiary')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-3">
                {{ Form::bsText('category', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($categories)) ], __('app.category')) }}
            </div>
            <div class="col-sm-3">
                {{ Form::bsText('project', null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($projects)) ], __('app.project')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('description', null, [ 'required' ], __('app.description')) }}
            </div>        
        </div>
        <div class="form-row">
            <div class="col-sm">
                <label>@lang('accounting::accounting.receipt')</label>
                <div class="form-row">
                    <div class="col-sm">
                        {{ Form::bsFile('receipt_picture', [ 'accept' => 'image/*' ], __($transaction->receipt_picture != null ? 'accounting::accounting.change_picture_of_receipt' : 'accounting::accounting.choose_picture_of_receipt')) }}
                    </div>
                    @isset($transaction->receipt_picture)
                        <div class="col-sm-auto">
                            {{ Form::bsCheckbox('remove_receipt_picture', 1, null, __('accounting::accounting.remove_receipt_picture')) }}<br>
                        </div>
                    @endisset
                </div>
            </div>
        </div>    
        <div class="form-row">
            <div class="col-sm-4">
                {{ Form::bsText('wallet_owner', null, [ ], __('accounting::accounting.wallet_owner')) }}
            </div>
            <div class="col-sm-8">
                {{ Form::bsText('remarks', null, [  ], __('app.remarks')) }}
            </div>
        </div>
        
        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>
    {!! Form::close() !!}
@endsection
