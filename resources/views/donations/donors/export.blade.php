<table>
    <thead>
        <tr>
            <th>@lang('app.name')</th>
            <th>@lang('donations.address')</th>
            <th>@lang('donations.zip')</th>
            <th>@lang('donations.city')</th>
            <th>@lang('donations.country')</th>
            <th>@lang('app.email')</th>
            <th>@lang('app.registered')</th>
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
                <td>{{ $donor->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
