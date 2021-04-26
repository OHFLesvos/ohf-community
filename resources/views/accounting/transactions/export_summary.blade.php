<table>
    <thead>
        <tr>
            <th>@lang('Month')</th>
            <th class="text-right">@lang('Income')</th>
            <th class="text-right">@lang('Spending')</th>
            <th class="text-right">@lang('Difference')</th>
            <th class="text-right">@lang('Wallet')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($months as $month)
            <tr>
                <td>{{ $month->formatLocalized('%B %Y') }}</td>
                <td class="text-right">=SUM('{{ $month->formatLocalized('%B %Y') }}'!B2:B1000)</td>
                <td class="text-right">=SUM('{{ $month->formatLocalized('%B %Y') }}'!C2:C1000)</td>
                <td class="text-right">=B{{ $loop->iteration + 1 }}-C{{ $loop->iteration + 1 }}</td>
                <td class="text-right">@if($loop->first)=D{{ $loop->iteration + 1 }}@else=D{{ $loop->iteration + 1 }}+E{{ $loop->iteration }}@endif</td>
            </tr>
        @endforeach
    </tbody>
</table>
