@if( ! $donations->isEmpty() )

    {{--  Individual donations  --}}
    <div class="table-responsive">
        <table class="table table-sm table-hover mt-2">
            <thead>
                <tr>
                    <th>@lang('fundraising.date')</th>
                    <th class="d-none d-sm-table-cell">@lang('fundraising.channel')</th>
                    <th>@lang('fundraising.purpose')</th>
                    <th class="d-none d-sm-table-cell">@lang('fundraising.reference')</th>
                    <th class="text-right">@lang('app.amount')</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($donations as $donation)
                    <tr>
                        <td><a href="{{ route('fundraising.donations.edit', [$donor, $donation]) }}">{{ $donation->date }}</a></td>
                        <td class="d-none d-sm-table-cell">{{ $donation->channel }}</td>
                        <td>{{ $donation->purpose }}</td>
                        <td class="d-none d-sm-table-cell">{{ $donation->reference }}</td>
                        <td class="text-right">
                            {{ $donation->currency }} {{ $donation->amount }}
                            @if($donation->currency != Config::get('fundraising.base_currency'))
                                ({{ Config::get('fundraising.base_currency') }} {{ $donation->exchange_amount }})
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $donations->links() }}
    
    {{--  Donations per year  --}}
    <table class="table table-sm mt-5">
        <thead>
            <tr>
                <th>@lang('fundraising.year')</th>
                <th class="text-right">@lang('app.amount')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($donor->donationsPerYear() as $donation)
                <tr>
                    <td>{{ $donation->year }}</td>
                    <td class="text-right" style="text-decoration: underline;">
                        {{ Config::get('fundraising.base_currency') }}
                        {{ $donation->total }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <div class="alert alert-info m-0">
        @lang('fundraising.no_donations_found')
    </div>
@endif
