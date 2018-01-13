<div class="card mb-4 bg-light">
    <div class="card-header p-2">
        <div class="form-row">
            <div class="col">
                <a href="{{ route('people.show', $person) }}" alt="View"><strong>{{ $person->family_name }} {{ $person->name }}</strong></a>
                <span>
                    @if(isset($person->gender))
                        @if($person->gender == 'f')@icon(female) 
                        @elseif($person->gender == 'm')@icon(male) 
                        @endif
                    @else
                        {{--  @icon(question-circle-o)   --}}
                        <button class="btn btn-warning btn-sm choose-gender" data-value="m" data-person="{{ $person->id }}">@icon(male)</button>
                        <button class="btn btn-warning btn-sm choose-gender" data-value="f" data-person="{{ $person->id }}">@icon(female)</button>
                    @endif
                </span>
                @if(isset($person->date_of_birth))
                    {{ $person->date_of_birth }} (age {{ $person->age }})
                @endif
                @if(isset($person->nationality))
                    {{ $person->nationality }}
                @endif
            </div>
            <div class="col-auto">
                <a href="{{ route('people.edit', $person) }}" alt="Edit">@icon(pencil)</a>
            </div>
        </div>
    </div>
    <div class="card-body p-2">
        @if(isset($person->police_no))
            <span class="d-block d-sm-inline">
                <small class="text-muted">Police Number:</small> 
                {{ $person->police_no }}
            </span>
        @endif
        @if(isset($person->case_no))
            <span class="d-block d-sm-inline">
                <small class="text-muted">Case Number:</small>
                {{ $person->case_no }}
            </span>
        @endif
        @if(isset($person->medical_no))
            <span class="d-block d-sm-inline">
                <small class="text-muted">Medical Number:</small>
                {{ $person->medical_no }}
            </span>
        @endif
        @if(isset($person->registration_no))
            <span class="d-block d-sm-inline">
                <small class="text-muted">Registration Number:</small>
                {{ $person->registration_no }}
            </span>
        @endif
        @if(isset($person->section_card_no))
            <span class="d-block d-sm-inline">
                <small class="text-muted">Section Card Number:</small>
                {{ $person->section_card_no }}
            </span>
        @endif
        @if(isset($person->temp_no))
            <span class="d-block d-sm-inline">
                <small class="text-muted">Temporary Number:</small>
                {{ $person->temp_no }}
            </span>
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
        <div class="row">
            <div class="col-sm mb-2 mb-sm-0">
                @php
                    $today = $person->todaysTransaction();
                @endphp
                Drachma: 
                <span>
                    @if($today > 0)
                        {{ $today }}
                        <small class="text-muted">on {{ $person->transactions()->orderBy('created_at', 'DESC')->first()->created_at }}</small>
                    @else
                        @if ($person->age == null || $person->age >= 12)
                            <button type="button" class="btn btn-primary btn-sm give-cash" data-value="2" data-person="{{ $person->id }}">2</button>
                        @endif
                        @if ($person->age == null || $person->age < 12)
                            <button type="button" class="btn btn-primary btn-sm give-cash" data-value="1" data-person="{{ $person->id }}">1</button>
                        @endif
                    @endif
                </span>
            </div>
            <div class="col-sm mb-2 mb-sm-0">
                @php
                    $boutique = $person->getBoutiqueCouponForJson($boutiqueThresholdDays);
                @endphp
                Boutique: <span>
                    @if($boutique != null)
                        {{ $boutique }}
                    @else
                        <button type="button" class="btn btn-primary btn-sm give-boutique-coupon" data-person="{{ $person->id }}">Coupon</button>
                    @endif
                </span>
            </div>
            <div class="col-sm">
                @if ($person->age == null || $person->age <= 4)
                    @php
                        $diapers = $person->getDiapersCouponForJson($diapersThresholdDays);
                    @endphp
                    Diapers: <span>
                        @if($diapers != null)
                            {{ $diapers }}
                        @else
                            <button type="button" class="btn btn-primary btn-sm give-diapers-coupon" data-person="{{ $person->id }}">Coupon</button>
                        @endif
                    </span>
                @endif
            </div>
        </div>    
    </div>
</div>
