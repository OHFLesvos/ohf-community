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
                <th>@lang('donations.donations') {{ Carbon\Carbon::now()->subYear()->year }}</th>
                <th>@lang('donations.donations') {{ Carbon\Carbon::now()->year }}</th>
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
                    <td>
                        {{ Config::get('donations.base_currency') }}
                        {{ $donor->amountPerYear(Carbon\Carbon::now()->subYear()->year) ?? 0 }}
                    </td>
                    <td>
                        {{ Config::get('donations.base_currency') }}
                        {{ $donor->amountPerYear(Carbon\Carbon::now()->year) ?? 0 }}
                    </td>
                @endcan
            </tr>
        @endforeach
    </tbody>
</table>
