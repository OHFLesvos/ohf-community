@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.maintenance'))

@section('content')
    {!! Form::open(['route' => ['people.updateMaintenance']]) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('people.cleanup_database')</div>
            <div class="card-body">
                <p>@lang('people.there_are_n_people_registered', [ 'num' => $num_people ]).</p>
                {{ Form::bsCheckbox('cleanup_no_coupons_since', null, null, __('people.remove_records_no_transaction_since_n_months', [
                    'months' => $months_no_transactions_since,
                    'num' => $persons_without_coupons_since,
                ])) }}
                {{ Form::bsCheckbox('cleanup_no_coupons_ever', null, null, __('people.remove_records_not_having_transaction_ever', [
                    'num' => $persons_without_coupons_ever,
                ])) }}
                {{ Form::bsCheckbox('cleanup_no_number', null, null, __('people.remove_records_without_number', [
                    'num' => $persons_without_number,
                ])) }}
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('app.apply')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
