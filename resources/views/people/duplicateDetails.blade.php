<a href="{{ route('people.show', $person) }}" target="_blank">@include('people.person-label', ['person' => $person ])</a><br>
@php
    $numTransactions = $person->couponHandouts()->count();
@endphp
<small class="text-muted">
    @if(isset($person->police_no))
        @lang('people.police_number'): {{ $person->police_no }}<br>
    @endif
    @if ($person->card_no != null)
        Card: {{ $person->card_no }} issued on {{ $person->card_issued }} ({{ $person->card_issued->diffForHumans() }})<br>
    @endif
    @if ($numTransactions > 0)
        Transactions: {{ $numTransactions }}<br>
        @php
            $lastTransactionDate = $person->couponHandouts()->orderBy('created_at', 'desc')->first()->date;
        @endphp
        Last transaction: {{ $lastTransactionDate }} ({{ (new Carbon\Carbon($lastTransactionDate))->diffForHumans() }})<br>
    @endif
    @if( $person->remarks != null )
        Remarks: <em>{{ $person->remarks }}</em><br>
    @endif
    Registered: {{ $person->created_at }} ({{ (new Carbon\Carbon($person->created_at))->diffForHumans() }})<br>
</small>