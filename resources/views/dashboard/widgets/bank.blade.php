@allowed(['do-bank-withdrawals', 'view-bank-reports'])
    <div class="card mb-4">
        <div class="card-header">
            Bank
            @allowed('do-bank-withdrawals')
                <a class="pull-right" href="{{ route('bank.index')  }}">Go to bank</a>
            @else
                <a class="pull-right" href="{{ route('reporting.bank.withdrawals')  }}">View bank report</a>
            @endallowed
        </div>
        <div class="card-body">
            <p>Served 
                @allowed('do-bank-withdrawals')
                    <a href="{{ route('bank.withdrawalTransactions') }}"><strong>{{ $num_people_served_today }}</strong></a> 
                @else
                    <strong>{{ $num_people_served_today }}</strong>
                @endallowed
            persons and handed out <strong>{{ $transaction_value_today }}</strong> drachma today.</p>
        </div>
    </div>
@endallowed