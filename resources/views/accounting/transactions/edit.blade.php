@extends('layouts.app')

@section('title', __('accounting.edit_transaction_number', ['number' => $transaction->receipt_no]))

@section('content')
    {!! Form::model($transaction, ['route' => ['accounting.transactions.update', $transaction], 'method' => 'put', 'files' => true]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', null, [ 'disabled' ], __('accounting.receipt_no')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsDate('date', null, [ 'required' ], __('app.date')) }}
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
                    {{ Form::bsSelect('category', collect($categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'required', 'placeholder' => '- ' . __('app.category') . ' -', 'error' => ! in_array($transaction->category, $categories) ? __('app.invalid_category_x', ['category' => $transaction->category]) : null ], __('app.category')) }}
                @else
                    {{ Form::bsText('category', null, [ 'required', 'list' => $categories ], __('app.category')) }}
                @endif
            </div>
            <div class="col-sm-3">
                @if($fixed_projects)
                    {{ Form::bsSelect('project', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.project') . ' -', 'error' => $transaction->project != null && ! in_array($transaction->project, $projects) ? __('app.invalid_project_x', ['project' => $transaction->project]) : null ], __('app.project')) }}
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
                <div class="form-row">
                    <div class="col-sm">
                        {{ Form::bsFile('receipt_picture', [ 'accept' => 'image/*' ], __(! empty($transaction->receipt_pictures) ? 'accounting.change_picture_of_receipt' : 'accounting.choose_picture_of_receipt')) }}
                    </div>
                    @if(! empty($transaction->receipt_pictures))
                        <div class="col-sm-auto">
                            {{ Form::bsCheckbox('remove_receipt_picture', 1, null, __('accounting.remove_receipt_picture')) }}<br>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-4">
                {{ Form::bsText('wallet_owner', null, [ ], __('accounting.wallet_owner')) }}
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
