@extends('layouts.app')

@section('title', __('fundraising.edit_donation_of', [ 'name' => $donor->full_name ]))

@section('content')

    {!! Form::model($donation, ['route' => ['fundraising.donations.update', $donor, $donation], 'method' => 'put']) !!}
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsDate('date', null, [ 'required', 'max' => Carbon\Carbon::today()->toDateString() ], __('app.date')) }}
            </div>
            <div class="col-md-auto">
                {{ Form::bsSelect('currency', $currencies, null, [ 'required', 'id' => 'currency' ], __('fundraising.currency')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('amount', null, [ 'required', 'autofocus', 'step' => 'any', 'id' => 'amount' ], __('app.amount'), __('app.write_decimal_point_as_comma')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('exchange_rate', null, [ 'step' => 'any' ], __('fundraising.exchange_rate'), __('app.write_decimal_point_as_comma') . '. ' . __('fundraising.leave_empty_for_automatic_calculation')) }}
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
        {{ Form::bsText('in_name_of', null, [ ], __('fundraising.in_name_of')) }}
        <p>{{ Form::bsCheckbox('thanked', null, null, __('fundraising.donor_thanked')) }}</p>
        
        <div class="row">
            <div class="col-auto pt-1">
                {{ Form::bsSubmitButton(__('app.update')) }}
            </div>
            <div class="col text-right">
                <small title="{{ $donation->created_at }}">@lang('app.registered') {{ $donation->created_at->diffForHumans() }}</small><br>
                <small title="{{ $donation->updated_at }}">@lang('app.last_updated') {{ $donation->updated_at->diffForHumans() }}</small><br>
            </div>
        </div>
    {!! Form::close() !!}

@endsection

@section('script')
    $(function(){
        $('#currency').on('change', function(){
            $('#amount').focus();
        });
    });
@endsection
