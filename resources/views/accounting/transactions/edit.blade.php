@extends('layouts.app')

@section('title', __('accounting.edit_transaction_number', ['number' => $transaction->receipt_no]))

@section('content')
    {!! Form::model($transaction, ['route' => ['accounting.transactions.update', $transaction], 'method' => 'put', 'files' => true]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', null, [ 'required', 'step' => '1', 'min' => 1 ], __('accounting.receipt_no')) }}
            </div>
            <div class="col-sm-auto">
                {{ Form::bsDate('date', null, [ 'required' ], __('app.date')) }}
            </div>
        </div>
        <div class="form-row">
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
            <div class="col-sm">
                @if($fixed_categories)
                    {{ Form::bsSelect('category', collect($categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'required', 'placeholder' => '- ' . __('app.category') . ' -', 'error' => ! in_array($transaction->category, $categories) ? __('app.invalid_category_x', ['category' => $transaction->category]) : null ], __('app.category')) }}
                @else
                    {{ Form::bsText('category', null, [ 'required', 'list' => $categories ], __('app.category')) }}
                @endif
            </div>
            @if($secondary_categories !== null)
                <div class="col-sm">
                    @if($fixed_secondary_categories)
                        {{ Form::bsSelect('secondary_category', collect($secondary_categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.secondary_category') . ' -', 'error' => $transaction->secondary_category != null && ! in_array($transaction->secondary_category, $secondary_categories) ? __('app.invalid_secondary_category_x', ['secondary_category' => $transaction->secondary_category]) : null ], __('app.secondary_category')) }}
                    @else
                        {{ Form::bsText('secondary_category', null, [ 'list' => $secondary_categories ], __('app.secondary_category')) }}
                    @endif
                </div>
            @endif
            <div class="col-sm">
                @if($fixed_projects)
                    {{ Form::bsSelect('project', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.project') . ' -', 'error' => $transaction->project != null && ! in_array($transaction->project, $projects) ? __('app.invalid_project_x', ['project' => $transaction->project]) : null ], __('app.project')) }}
                @else
                    {{ Form::bsText('project', null, [ 'list' => $projects ], __('app.project')) }}
                @endif
            </div>
        </div>
        <div class="form-row">
            @if($locations !== null)
                <div class="col-sm">
                    @if($fixed_locations)
                        {{ Form::bsSelect('location', collect($locations)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.location') . ' -', 'error' => $transaction->location != null && ! in_array($transaction->location, $locations) ? __('app.invalid_location_x', ['location' => $transaction->location]) : null ], __('app.location')) }}
                    @else
                        {{ Form::bsText('location', null, [ 'list' => $locations ], __('app.location')) }}
                    @endif
                </div>
            @endif
            @if($cost_centers !== null)
                <div class="col-sm">
                    @if($fixed_cost_centers)
                        {{ Form::bsSelect('cost_center', collect($cost_centers)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('accounting.cost_center') . ' -', 'error' => $transaction->cost_center != null && ! in_array($transaction->cost_center, $cost_centers) ? __('accounting.invalid_cost_center_x', ['cost_center' => $transaction->cost_center]) : null ], __('accounting.cost_center')) }}
                    @else
                        {{ Form::bsText('cost_center', null, [ 'list' => $cost_centers ], __('accounting.cost_center')) }}
                    @endif
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-sm-12">
                {{ Form::bsText('description', null, [ 'required' ], __('app.description')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm">
                <label>@lang('accounting.receipt')</label>
                <div class="form-row">
                    <div class="col-sm">
                        {{ Form::bsFile('receipt_picture', [ 'accept' => 'image/*,application/pdf' ], __(! empty($transaction->receipt_pictures) ? 'accounting.change_picture_of_receipt' : 'accounting.choose_picture_of_receipt')) }}
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
