<div class="card mb-4">
    <div class="card-header p-2">
        <div class="form-row">
            <div class="col">
                <strong>{{ $person->family_name }} {{ $person->name }}</strong>
                @if(isset($person->gender))
                    @if($person->gender == 'f')@icon(female) 
                    @elseif($person->gender == 'm')@icon(male) 
                    @endif
                @endif
                @if(isset($person->date_of_birth))
                    {{ $person->date_of_birth }} (age {{ $person->age }})
                @endif
                @if(isset($person->nationality))
                    {{ $person->nationality }}
                @endif
            </div>
            <div class="col-auto">
                <a href="{{ route('people.show', $person) }}" alt="View">@icon(file-text-o)</a> &nbsp;
                <a href="{{ route('people.edit', $person) }}" alt="Edit">@icon(pencil)</a>
            </div>
        </div>
    </div>
    <div class="card-body p-2 d">
        @if(isset($person->police_no))
            <small class="text-muted">Police Number:</small> 
            {{ $person->police_no }}
        @endif
        @if(isset($person->case_no))
            <small class="text-muted">Case Number:</small>
            {{ $person->case_no }}
        @endif
        @if(isset($person->medical_no))
            <small class="text-muted">Medical Number:</small>
            {{ $person->medical_no }}
        @endif
        @if(isset($person->registration_no))
            <small class="text-muted">Registration Number:</small>
            {{ $person->registration_no }}
        @endif
        @if(isset($person->section_card_no))
            <small class="text-muted">Section Card Number:</small>
            {{ $person->section_card_no }}
        @endif
        @if(isset($person->temp_no))
            <small class="text-muted">Temporary Number:</small>
            {{ $person->temp_no }}
        @endif
        @if (!isset($person->police_no) && !isset($person->case_no) && !isset($person->medical_no) && !isset($person->registration_no) && !isset($person->section_card_no) && !isset($person->temp_no))
            <small class="text-muted">No number registered!</small>
        @endif
        @if(isset($person->remarks))
            <div>
                <em>{{ $person->remarks }}</em>
            </div>
        @endif
    </div>
    <div class="card-footer p-2">
        @php
            $today = $person->todaysTransaction();
        @endphp
        @if($today > 0)
            {{ $today }} drachma
            <small class="text-muted">on {{ $person->transactions()->orderBy('created_at', 'DESC')->first()->created_at }}</small>
        @else
            @if ($person->age == null || $person->age >= 12)
                <button type="button" class="btn btn-primary btn-sm">2 drachma</button>
            @endif
            @if ($person->age == null || $person->age < 12)
                <button type="button" class="btn btn-primary btn-sm">1 drachma</button>
            @endif
        @endif
    </div>
</div>
