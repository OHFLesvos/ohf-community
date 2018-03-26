@extends('bank.layout')

@section('title', __('people.bank'))

@section('wrapped-content')

    {!! Form::open(['route' => ['bank.storeDeposit']]) !!}
        <div class="card mb-4">
            <div class="card-header">@lang('people.deposit_coupons')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-sm mb-2 mb-md-0">
                        <div class="form-group">
                            {{ Form::select('project', $projects, null, [ 'placeholder' => __('people.select_project'), 'class' => 'form-control'.($errors->has('project') ? ' is-invalid' : ''), 'autofocus', 'required' ]) }}
                            @if ($errors->has('project'))
                                <span class="invalid-feedback">{{ $errors->first('project') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm mb-2 mb-md-0">
                        {{ Form::bsNumber('amount', null, [ 'placeholder' => __('app.amount'), 'required' ], '') }}
                    </div>
                    <div class="col-sm mb-2 mb-md-0">
                        <div class="form-group">
                            {{ Form::select('coupon_type', $couponTypes, $selectedCouponType, [ 'placeholder' => __('people.select_coupon_type'), 'class' => 'form-control'.($errors->has('coupon_type') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('coupon_type'))
                                <span class="invalid-feedback">{{ $errors->first('coupon_type') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm mb-2 mb-md-0">
                        {{ Form::bsDate('date', \Carbon\Carbon::now()->toDateString(), [ 'required', 'max' => \Carbon\Carbon::now()->toDateString() ], '') }}
                    </div>
                    <div class="col-sm-auto">
                        {{ Form::bsSubmitButton(__('app.add'), 'plus-circle') }}
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    <div id="stats" class="row justify-content-md-center my-5">
        <div class="col col-lg-2">
        </div>
        <div class="col-md-auto p-4 lead">
            @if(count($todaysReturns) > 0)
                <table style="border-spacing: 0.5em; border-collapse: separate;">
                @foreach($todaysReturns as $k => $v)
                    <tr>
                        <td class="text-right align-top"><strong>{{ $k }}</strong></td>
                        <td class="align-top">
                            @foreach($v as $t)
                                {{ $t['amount'] }}
                                <small class="text-muted pl-3">@lang('app.updated_by') {{ $t['author'] }} {{ $t['date'] }}</small>
                                <br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </table>
            @else
                @lang('people.no_coupons_registered_so_far')
            @endif
        </div>
        <div class="col col-lg-2">
        </div>
    </div>

@endsection