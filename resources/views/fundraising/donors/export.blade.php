<table>
    <thead>
        <tr>
            <th>@lang('fundraising.first_name')</th>
            <th>@lang('fundraising.last_name')</th>
            <th>@lang('fundraising.company')</th>
            <th>@lang('fundraising.street')</th>
            <th>@lang('fundraising.zip')</th>
            <th>@lang('fundraising.city')</th>
            <th>@lang('fundraising.country')</th>
            <th>@lang('app.email')</th>
            <th>@lang('fundraising.phone')</th>
            <th>@lang('fundraising.correspondence_language')</th>
            <th>@lang('app.registered')</th>
            @can('list', App\Donation::class)
                <th>@lang('fundraising.donations') {{ Carbon\Carbon::now()->subYear()->year }}</th>
                <th>@lang('fundraising.donations') {{ Carbon\Carbon::now()->year }}</th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($donors as $donor)
            <tr>
                <td>{{ $donor->first_name }}</td>
                <td>{{ $donor->last_name }}</td>
                <td>{{ $donor->company }}</td>
                <td>{{ $donor->street }}</td>
                <td>{{ $donor->zip }}</td>
                <td>{{ $donor->city }}</td>
                <td>{{ $donor->country_name }}</td>
                <td>{{ $donor->email }}</td>
                <td>{{ $donor->phone }}</td>
                <td>{{ $donor->language }}</td>
                <td>{{ $donor->created_at }}</td>
                @can('list', App\Donation::class)
                    <td>{{ $donor->amountPerYear(Carbon\Carbon::now()->subYear()->year) ?? 0 }}</td>
                    <td>{{ $donor->amountPerYear(Carbon\Carbon::now()->year) ?? 0 }}</td>
                @endcan
            </tr>
        @endforeach
    </tbody>
</table>
