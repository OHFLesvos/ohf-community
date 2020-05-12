@extends('layouts.app')

@section('title', __('accounting.register_new_transaction'))

@section('content')
    {!! Form::open(['route' => ['accounting.transactions.store' ], 'files' => true]) !!}
        @if(count($wallets) > 1)
            {{ Form::bsSelect('wallet_id', $wallets, optional($wallet)->id, [], __('accounting.wallet')) }}
        @else
            {{ Form::hidden('wallet_id', array_keys($wallets)[0]) }}
        @endif
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', $newReceiptNo, [ 'disabled' ], __('accounting.receipt_no')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsDate('date', Carbon\Carbon::today(), [ 'required', 'autofocus' ], __('app.date')) }}
            </div>
            <div class="col-sm-auto pb-3">
            {{ Form::bsRadioInlineList('type', [ 'income' => __('accounting.income'), 'spending' => __('accounting.spending') ], 'spending', __('app.type')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('amount', null, [ 'required', 'step' => 'any', 'min' => 0], __('app.amount'), __('app.write_decimal_point_as_comma')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('beneficiary', null, [ 'required', 'list' => $beneficiaries ], __('accounting.beneficiary')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-3">
                @if($fixed_categories)
                    {{ Form::bsSelect('category', collect($categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'required', 'placeholder' => '- ' . __('app.category') . ' -' ], __('app.category')) }}
                @else
                    {{ Form::bsText('category', null, [ 'required', 'list' => $categories ], __('app.category')) }}
                @endif
            </div>
            <div class="col-sm-3">
                @if($fixed_projects)
                    {{ Form::bsSelect('project', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.project') . ' -' ], __('app.project')) }}
                @else
                    {{ Form::bsText('project', null, [ 'list' => $projects ], __('app.project')) }}
                @endif
            </div>
            <div class="col-sm">
                {{ Form::bsText('description', null, [ 'required' ], __('app.description')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm">
                <label>@lang('accounting.receipt')</label>
                {{ Form::bsFile('receipt_picture', [ 'accept' => 'image/*' ], __('accounting.choose_picture_of_receipt')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-4">
                {{ Form::bsText('wallet_owner', Auth::user()->name, [ ], __('accounting.wallet_owner')) }}
            </div>
            <div class="col-sm-8">
                {{ Form::bsText('remarks', null, [  ], __('app.remarks')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.add')) }}
            <button type="submit" name="submit" value="save_and_continue" class="btn btn-secondary">@icon(arrow-right) @lang('app.save_and_continue')</button>
        </p>
    {!! Form::close() !!}
@endsection

@section('script')
$(function () {
    $('input[name="date"]').on('change', function () {
        $('input[name="amount"]').focus();
    });
});
@endsection
