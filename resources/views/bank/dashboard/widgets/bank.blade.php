@extends('dashboard.widgets.base')

@section('widget-title', __('bank.bank'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('people.served_n_persons_and_handed_out_n_today', [
                'persons' => Gate::allows('do-bank-withdrawals') ? '<a href="' . route('bank.withdrawal.transactions') . '">' . $persons . '</a>': $persons,
                'coupons' => $coupons
            ])
        </p>
    </div>
@endsection
