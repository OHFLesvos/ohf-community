@extends('layouts.app')

@section('title', __('accounting.register_new_transaction'))

@section('content')
    {!! Form::open(['route' => ['accounting.transactions.store' ], 'files' => true]) !!}
        @if($wallets->count() > 1)
            {{ Form::bsSelect('wallet_id', $wallets->pluck('name', 'id'), optional($wallet)->id, [], __('accounting.wallet')) }}
        @else
            {{ Form::hidden('wallet_id', $wallets[0]['id']) }}
        @endif
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', $wallets->count() == 1 ? $wallets[0]['new_receipt_no'] : '', [ 'required', 'step' => '1', 'min' => 1 ], __('accounting.receipt_no')) }}
            </div>
            <div class="col-sm-auto">
                {{ Form::bsDate('date', Carbon\Carbon::today(), [ 'required' ], __('app.date')) }}
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
                {{ Form::bsText('attendee', null, [ 'list' => $attendees ], __('accounting.attendee')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm">
                @if($fixed_categories)
                    {{ Form::bsSelect('category', collect($categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'required', 'placeholder' => '- ' . __('app.category') . ' -' ], __('app.category')) }}
                @else
                    {{ Form::bsText('category', null, [ 'required', 'list' => $categories ], __('app.category')) }}
                @endif
            </div>
            @if($secondary_categories !== null)
                <div class="col-sm">
                    @if($fixed_secondary_categories)
                        {{ Form::bsSelect('secondary_category', collect($secondary_categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.secondary_category') . ' -' ], __('app.secondary_category')) }}
                    @else
                        {{ Form::bsText('secondary_category', null, [ 'required', 'list' => $secondary_categories ], __('app.secondary_category')) }}
                    @endif
                </div>
            @endif
            <div class="col-sm">
                @if($fixed_projects)
                    {{ Form::bsSelect('project', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.project') . ' -' ], __('app.project')) }}
                @else
                    {{ Form::bsText('project', null, [ 'list' => $projects ], __('app.project')) }}
                @endif
            </div>
        </div>
        <div class="form-row">
            @if($locations !== null)
                <div class="col-sm">
                    @if($fixed_locations)
                        {{ Form::bsSelect('location', collect($locations)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.location') . ' -' ], __('app.location')) }}
                    @else
                        {{ Form::bsText('location', null, [ 'list' => $locations ], __('app.location')) }}
                    @endif
                </div>
            @endif
            @if($cost_centers !== null)
                <div class="col-sm">
                    @if($fixed_cost_centers)
                        {{ Form::bsSelect('cost_center', collect($cost_centers)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('accounting.cost_center') . ' -' ], __('accounting.cost_center')) }}
                    @else
                        {{ Form::bsText('cost_center', null, [ 'list' => $cost_centers ], __('accounting.cost_center')) }}
                    @endif
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsText('description', null, [ 'required' ], __('app.description')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('remarks', null, [  ], __('app.remarks')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm">
                <label>@lang('accounting.receipt')</label>
                {{ Form::bsFile('receipt_picture[]', [ 'accept' => 'image/*,application/pdf', 'multiple' ], __('accounting.choose_picture_of_receipt')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.add')) }}
            <button type="submit" name="submit" value="save_and_continue" class="btn btn-secondary">@icon(arrow-right) @lang('app.save_and_continue')</button>
        </p>
    {!! Form::close() !!}

@endsection

@section('script')
var new_receipt_numbers = @json($wallets->pluck('new_receipt_no', 'id'));
function setNewReceiptNumber() {
    var value = new_receipt_numbers[$('select[name="wallet_id"]').val()];
    $('input[name="receipt_no"]').val(value);
}

$(function () {
    $('input[name="date"]').on('change', function () {
        $('input[name="amount"]').focus();
    });
    @if($wallets->count() > 1)
    $('select[name="wallet_id"]').on('change', setNewReceiptNumber);
    setNewReceiptNumber();
    @endif
});
@endsection
