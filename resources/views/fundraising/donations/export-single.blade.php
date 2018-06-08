<table class="table table-sm table-hover">
    <thead>
        <tr>
            <th>@lang('app.date')</th>
            <th>@lang('fundraising.channel')</th>
            <th>@lang('fundraising.purpose')</th>
            <th>@lang('fundraising.reference')</th>
            <th>@lang('fundraising.in_name_of')</th>
            <th>@lang('fundraising.thanked')</th>
            <th>@lang('app.amount')</th>
            <th>@lang('fundraising.exchange_amount')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($donations as $donation)
            <tr>
                <td>{{ $donation->date }}</td>
                <td>{{ $donation->channel }}</td>
                <td>{{ $donation->purpose }}</td>
                <td>{{ $donation->reference }}</td>
                <td>{{ $donation->in_name_of }}</td>
                <td>{{ optional($donation->thanked)->toDateString() }}</td>
                <td>{{ $donation->amount }}</td>
                <td>{{ $donation->exchange_amount }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
