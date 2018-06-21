<div class="card mb-4">
    <div class="card-header">
        @lang('accounting.accounting') {{ $monthName }}
        <span class="pull-right">
            @can('create', App\MoneyTransaction::class)
                <a href="{{ route('accounting.transactions.create')  }}">@lang('app.register')</a> &nbsp;
            @endcan
            <a href="{{ route('accounting.transactions.summary')  }}">@lang('app.view')</a>
        </span>
    </div>
    <div class="card-body pb-2">
        <p>
            @isset($income)
                @lang('accounting.income'): {{ number_format($income, 2) }}<br>
            @endif
            @isset($spending)
                @lang('accounting.spending'): {{ number_format($spending, 2) }}
            @endif
        </p>
    </div>
</div>
