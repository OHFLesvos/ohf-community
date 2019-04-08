@extends('bank::layout')

@section('title', __('bank::bank.bank'))

@section('wrapped-content')

    {!! Form::open(['route' => ['bank.storeDeposit']]) !!}
        <div class="card mb-4">
            <div class="card-header">@lang('people::people.deposit_coupons')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-sm mb-2 mb-md-0">
                        <div class="form-group">
                            {{ Form::select('project', $projects, null, [ 'placeholder' => __('people::people.select_project'), 'class' => 'form-control'.($errors->has('project') ? ' is-invalid' : ''), 'autofocus', 'required' ]) }}
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
                            {{ Form::select('coupon_type', $couponTypes, $selectedCouponType, [ 'placeholder' => __('people::people.select_coupon_type'), 'class' => 'form-control'.($errors->has('coupon_type') ? ' is-invalid' : ''), 'required' ]) }}
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
            @if(count($audits) > 0)
                <p class="text-center">@lang('people::people.todays_transactions'):</p>
                @foreach($audits as $audit)
                    <p class="mb-3 mb-sm-1">
                        @lang($audit['amount_diff'] > 0 ? 'people.user_added_coupons_from_project' : 'people.user_removed_coupons_from_project', [
                            'user' => $audit['user'],
                            'amount' => abs($audit['amount_diff']),
                            'coupons' => $audit['coupon'],
                            'project' => $audit['project'],
                            'date' => $audit['date'],
                        ])
                        <small class="text-muted d-block d-sm-inline-block ml-0 ml-sm-2" title="{{ $audit['created_at'] }}">{{ $audit['created_at']->diffForHumans() }}</small>
                    </p>
                @endforeach
            @else
                @lang('bank::coupons.no_coupons_registered_so_far')
            @endif
        </div>
        <div class="col col-lg-2">
        </div>
    </div>

@endsection