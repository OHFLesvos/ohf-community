@extends('layouts.app')

@section('title', __('fundraising.edit_donation'))

@section('content')

    <div class="card mb-4">
        <div class="card-header">@lang('fundraising.edit_donation_of', [ 'name' => $donor->name])</div>
        <div class="card-body pb-0">    
            {!! Form::model($donation, ['route' => ['fundraising.donations.update', $donor, $donation], 'method' => 'put']) !!}
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsDate('date', null, [ 'required' ], __('fundraising.date')) }}
                    </div>
                    <div class="col-md-auto">
                        {{ Form::bsSelect('currency', $currencies, null, [ 'required', 'id' => 'currency' ], __('fundraising.currency')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsNumber('amount', null, [ 'required', 'autofocus', 'step' => 'any', 'id' => 'amount' ], __('app.amount'), __('fundraising.write_decimal_point_as_comma')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsNumber('exchange_rate', null, [ 'step' => 'any' ], __('fundraising.exchange_rate'), __('fundraising.write_decimal_point_as_comma') . '. ' . __('fundraising.leave_empty_for_automatic_calculation')) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('channel', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($channels)) ], __('fundraising.channel')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('purpose', null, [ ], __('fundraising.purpose')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('reference', null, [ ], __('fundraising.reference')) }}
                    </div>
                </div>
                <p>
                    {{ Form::bsSubmitButton(__('app.update')) }}
                </p>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('script')
    $(function(){
        $('#currency').on('change', function(){
            $('#amount').focus();
        });
    });
@endsection
