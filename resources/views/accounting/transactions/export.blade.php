<table>
    <thead>
        <tr>
            <th>@lang('app.date')</th>
            <th class="text-right">@lang('accounting.income')</th>
            <th class="text-right">@lang('accounting.spending')</th>
            <th>@lang('accounting.receipt') #</th>
            <th>@lang('accounting.beneficiary')</th>
            <th>@lang('app.project')</th>
            <th>@lang('app.description')</th>
            <th>@lang('app.registered')</th>
            <th>@lang('app.author')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->date }}</td>
                <td class="text-right">@if($transaction->type == 'income'){{ $transaction->amount }}@endif</td>
                <td class="text-right">@if($transaction->type == 'spending'){{ $transaction->amount }}@endif</td>
                <td>{{ $transaction->receipt_no }}</td>
                <td>{{ $transaction->beneficiary }}</td>
                <td>{{ $transaction->project }}</td>
                <td>{{ $transaction->description }}</td>
                @php
                    $audit = $transaction->audits()->latest()->first();
                @endphp
                <td>{{ $transaction->created_at }}</td>
                <td>@isset($audit){{ $audit->getMetadata()['user_name'] }}@endisset</td>
            </tr>
        @endforeach
    </tbody>
</table>
