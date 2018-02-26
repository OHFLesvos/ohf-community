<table class="table table-sm table-hover">
    <thead>
        <tr>
            <th>@lang('donations.date')</th>
            <th>@lang('donations.origin')</th>
            <th>@lang('donations.amount')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($donations as $donation)
            <tr>
                <td>{{ $donation->date }}</td>
                <td>{{ $donation->origin }}</td>
                <td>
                    {{ $donation->currency }} {{ $donation->amount }}
                    @if($donation->currency != Config::get('donations.base_currency'))
                        ({{ Config::get('donations.base_currency') }} {{ $donation->exchange_amount }})
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
