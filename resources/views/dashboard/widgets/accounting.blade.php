<div class="card mb-4">
    <div class="card-header">
        @lang('accounting.accounting') {{ $monthName }}
        <a class="pull-right" href="{{ route('accounting.transactions.summary')  }}">@lang('app.view')</a>
    </div>
    <div class="card-body pb-2">
        <p>
            @isset($income)
                @lang('accounting.income'): {{ $income }}<br>
            @endif
            @isset($spending)
                @lang('accounting.spending'): {{ $spending }}
            @endif
        </p>
    </div>
</div>
