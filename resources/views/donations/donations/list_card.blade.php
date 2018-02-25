<div class="card">
    <div class="card-header">@lang('donations.donations')</div>
    <div class="card-body">
        @if( ! $donations->isEmpty() )
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Origin</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td>{{ $donation->date }}</td>
                            <td>{{ $donation->origin }}</td>
                            <td class="text-right">
                                {{ $donation->currency }} {{ $donation->amount }}
                                @if($donation->currency != Config::get('donations.base_currency'))
                                    ({{ Config::get('donations.base_currency') }} {{ $donation->exchange_amount }})
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $donations->links() }}

            <table class="table table-sm table-hover mt-5 mb-0">
                <tbody>
                    <tr>
                        <td>@lang('app.total') {{ Carbon\Carbon::now()->year }}</td>
                        <td class="text-right" style="text-decoration: underline;">
                            {{ Config::get('donations.base_currency') }}
                            {{ $donor->amountPerYear(Carbon\Carbon::now()->year) ?? 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>@lang('app.total') {{ Carbon\Carbon::now()->subYear()->year }}</td>
                        <td class="text-right" style="text-decoration: underline;">
                            {{ Config::get('donations.base_currency') }}
                            {{ $donor->amountPerYear(Carbon\Carbon::now()->subYear()->year) ?? 0 }}
                        </td>
                    </tr>
                </tbody>
            </table>

        @else
            <div class="alert alert-info m-0">
                @lang('donations.no_donations_found')
            </div>
        @endif

    </div>
</div>