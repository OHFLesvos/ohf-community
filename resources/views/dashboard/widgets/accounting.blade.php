<div class="card mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">@lang('accounting.accounting')</div>
            <div class="col-auto">
                @can('create', App\MoneyTransaction::class)
                    <a href="{{ route('accounting.transactions.create')  }}" class="mr-1 btn btn-sm btn-outline-primary" title="@lang('app.register')">@icon(plus-circle)<span class="d-none d-xl-inline"> @lang('app.register')</span></a>
                @endcan
                <a href="{{ route('accounting.transactions.summary')  }}" class="btn btn-sm btn-outline-primary" title="@lang('app.view')">@icon(calculator)<span class="d-none d-xl-inline"> @lang('app.view')</span></a>
            </div>
        </div>
    </div>
    <table class="table mb-0">
        <tr><th colspan="2">{{ $monthName }}:</td></tr>
        @isset($income)
            <tr><td>@lang('accounting.income')</td><td class="text-right">{{ number_format($income, 2) }}</td></tr>
        @endif
        @isset($spending)
            <tr><td>@lang('accounting.spending')</td><td class="text-right">{{ number_format($spending, 2) }}</td></tr>
        @endif
    </table>
</div>
