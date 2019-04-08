<a href="{{ route('people.show', $person) }}" target="_blank">@include('people::person-label', ['person' => $person ])</a><br>
@php
    $numTransactions = $person->couponHandouts()->count();
@endphp
<small class="text-muted">
    @if(isset($person->mother))
        Mother:
        <a href="{{ route('people.show', $person->mother) }}" target="_blank">
            @include('people::person-label', ['person'=> $person->mother, 'prefix' => 'Mother'])
        </a><br>
    @endif
    @if(isset($person->father))
        Father:
        <a href="{{ route('people.show', $person->father) }}" target="_blank">
            @include('people::person-label', ['person'=> $person->father, 'prefix' => 'Father'])
        </a><br>
    @endif
    @if(isset($person->partner))
        Partner:
        <a href="{{ route('people.show', $person->partner) }}" target="_blank">
            @include('people::person-label', ['person'=> $person->partner, 'prefix' => 'Partner'])
        </a><br>
    @endif
    @if(count($person->children) > 0)
        @foreach($person->children->sortByDesc('age') as $child) 
            Child:
            <a href="{{ route('people.show', $child) }}" target="_blank">
                @include('people::person-label', ['person' => $child, 'prefix' => 'Child'])
            </a><br>
        @endforeach
    @endif    
    @if(isset($person->police_no))
        @lang('people::people.police_number'): {{ $person->police_no }}<br>
    @endif
    @if(isset($person->case_no_hash))
        @lang('people::people.case_number'): {{ $person->case_no_hash != null ? __('app.yes') : __('app.no') }}<br>
    @endif
    @if(isset($person->registration_no))
        @lang('people::people.registration_number'): {{ $person->registration_no }}<br>
    @endif
    @if(isset($person->section_card_no))
        @lang('people::people.section_card_number'): {{ $person->section_card_no }}<br>
    @endif
    @if ($person->card_no != null)
        Card: {{ $person->card_no }} issued on {{ $person->card_issued }} ({{ (new Carbon\Carbon($person->card_issued))->diffForHumans() }})<br>
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