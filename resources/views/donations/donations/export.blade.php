<table class="table table-sm table-hover">
    <thead>
        <tr>
            <th>@lang('donations.date')</th>
            <th>@lang('donations.channel')</th>
            <th>@lang('donations.purpose')</th>
            <th>@lang('donations.reference')</th>
            <th>@lang('donations.amount')</th>
            <th>@lang('donations.exchange_amount')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($donations as $donation)
            <tr>
                <td>{{ $donation->date }}</td>
                <td>{{ $donation->channel }}</td>
                <td>{{ $donation->purpose }}</td>
                <td>{{ $donation->reference }}</td>
                <td>{{ $donation->amount }}</td>
                <td>{{ $donation->exchange_amount }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
