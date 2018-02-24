<div class="card mb-4">
    <div class="card-header">
        @lang('people.bank')
        @allowed('do-bank-withdrawals')
            <a class="pull-right" href="{{ route('bank.index')  }}">@lang('people.go_to_bank')</a>
        @else
            <a class="pull-right" href="{{ route('reporting.bank.withdrawals')  }}">@lang('people.view_bank_report')</a>
        @endallowed
    </div>
    <div class="card-body pb-2">
        <p>
            @lang('people.served_n_persons_and_handed_out_n_today', [ 
                'num_people_served_today' => Gate::allows('do-bank-withdrawals') ? '<a href="' . route('bank.withdrawalTransactions') . '">' . $num_people_served_today . '</a>': $num_people_served_today, 
                'transaction_value_today' => $transaction_value_today 
            ])
        </p>
    </div>
</div>
