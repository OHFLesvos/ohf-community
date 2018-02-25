<table>
    <thead>
        <tr>
            <th>@lang('app.name')</th>
            <th>@lang('donations.address')</th>
            <th>@lang('donations.zip')</th>
            <th>@lang('donations.city')</th>
            <th>@lang('donations.country')</th>
            <th>@lang('app.email')</th>
            <th>@lang('donations.phone')</th>
            <th>@lang('app.registered')</th>
            @can('list', App\Donation::class)
                @php
                    $currenciesLast = App\Donation::currenciesPerYear(Carbon\Carbon::now()->subYear()->year);
                    $countLast = $currenciesLast->count();
                    $currenciesCurrent = App\Donation::currenciesPerYear(Carbon\Carbon::now()->year);
                    $countCurrent = $currenciesCurrent->count();
                @endphp
                @if($countLast > 0)
                    <th colspan="{{ $countLast }}">@lang('donations.donations') {{ Carbon\Carbon::now()->subYear()->year }}</th>
                @endif
                @if($countCurrent > 0)
                    <th colspan="{{ $countCurrent }}">@lang('donations.donations') {{ Carbon\Carbon::now()->year }}</th>
                @endif
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($donors as $donor)
            <tr>
                <td>{{ $donor->name }}</td>
                <td>{{ $donor->address }}</td>
                <td>{{ $donor->zip }}</td>
                <td>{{ $donor->city }}</td>
                <td>{{ $donor->country }}</td>
                <td>{{ $donor->email }}</td>
                <td>{{ $donor->phone }}</td>
                <td>{{ $donor->created_at }}</td>
                @can('list', App\Donation::class)
                    @foreach($currenciesLast as $currency)
                        @php
                            $donationsLast = $donor->amountPerYear(Carbon\Carbon::now()->subYear()->year, $currency);
                        @endphp
                        <td class="text-right">
                            @isset($donationsLast)
                                {{ $donationsLast->currency }} {{ $donationsLast->total }}
                            @endisset
                        </td>
                    @endforeach
                    @foreach($currenciesCurrent as $currency)
                        @php
                            $donationsCurrent = $donor->amountPerYear(Carbon\Carbon::now()->year, $currency);
                        @endphp
                        <td class="text-right">
                            @isset($donationsCurrent)
                                {{ $donationsCurrent->currency }} {{ $donationsCurrent->total }}
                            @endisset
                        </td>
                    @endforeach
                @endcan
            </tr>
        @endforeach
    </tbody>
</table>
