<div class="card @if(isset($bottom_margin))mb-{{ $bottom_margin }}@else mb-4 @endif bg-light @isset($border)border-{{ $border }}@endisset">
    @php
        $frequentVisitor = $person->frequentVisitor;
    @endphp
    <div class="card-header p-2" @if($frequentVisitor) style="background:lightgoldenrodyellow;" @endif >
        <div class="form-row">
            <div class="col">
                <a href="{{ route('people.show', $person) }}" alt="View"><strong>{{ $person->family_name }} {{ $person->name }}</strong></a>
                <span>
                    @if(isset($person->gender))
                        @if($person->gender == 'f')@icon(female) 
                        @elseif($person->gender == 'm')@icon(male) 
                        @endif
                    @else
                        <button class="btn btn-warning btn-sm choose-gender" data-value="m" data-person="{{ $person->id }}" title="Male">@icon(male)</button>
                        <button class="btn btn-warning btn-sm choose-gender" data-value="f" data-person="{{ $person->id }}" title="Female">@icon(female)</button>
                    @endif
                </span>
                <span class="form-inline d-inline">
                    @if(isset($person->date_of_birth))
                        {{ $person->date_of_birth }} (age {{ $person->age }})
                    @else
                        <button class="btn btn-warning btn-sm choose-date-of-birth" data-person="{{ $person->id }}" title="Set date of birth">@icon(calendar-plus-o)</button>
                    @endif
                </span>
                <span class="form-inline d-inline">
                    @if(isset($person->nationality))
                        {{ $person->nationality }}
                    @else
                        <button class="btn btn-warning btn-sm choose-nationality" data-person="{{ $person->id }}" title="Set nationality">@icon(globe)</button>
                    @endif
                </span>
                @if($frequentVisitor)
                    <span class="text-warning" title="Frequent visitor">@icon(star)</span>
                @endif
                <a href="{{ route('people.edit', $person) }}" title="Edit">@icon(pencil)</a>
            </div>
            <div class="col-auto">
                @icon(id-card)
                <a href="javascript:;" class="register-card" data-person="{{ $person->id }}" data-card="{{ $person->card_no }}">
                    @if(isset($person->card_no))
                        <strong>{{ substr($person->card_no, 0, 7) }}</strong>
                    @else
                        @lang('app.register')
                    @endif
                </a>
            </div>
        </div>
    </div>
    @if (isset($person->police_no) || isset($person->case_no) || isset($person->medical_no) || isset($person->registration_no) || isset($person->section_card_no) || isset($person->temp_no) || isset($person->remarks))
        <div class="card-body p-2">
            @if(isset($person->police_no))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people.police_number'):</small> 
                    <span class="pr-2">05/{{ $person->police_no }}</span>
                </span>
            @endif
            @if(isset($person->case_no))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people.case_number'):</small>
                    <span class="pr-2">{{ $person->case_no }}</span>
                </span>
            @endif
            @if(isset($person->medical_no))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people.medical_number'):</small>
                    <span class="pr-2">{{ $person->medical_no }}</span>
                </span>
            @endif
            @if(isset($person->registration_no))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people.registration_number'):</small>
                    <span class="pr-2">{{ $person->registration_no }}</span>
                </span>
            @endif
            @if(isset($person->section_card_no))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people.section_card_number'):</small>
                    <span class="pr-2">{{ $person->section_card_no }}</span>
                </span>
            @endif
            @if(isset($person->temp_no))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people.temporary_number'):</small>
                    <span class="pr-2">{{ $person->temp_no }}</span>
                </span>
            @endif
            @if(isset($person->remarks))
                <div>
                    <em class="text-info">{{ $person->remarks }}</em>
                </div>
            @endif
        </div>
    @endif
    <div class="card-footer p-0 px-2 pt-2">
        <div class="form-row">
            @forelse($couponTypes as $coupon)
                @if($person->eligibleForCoupon($coupon))
                    <div class="col-sm-auto mb-2">
                        @if($person->eligibleForCoupon($coupon))
                            @php
                                $lastHandout = $person->canHandoutCoupon($coupon);
                            @endphp
                            @isset($lastHandout)
                                <button type="button" class="btn btn-secondary btn-sm btn-block" disabled data-coupon="{{ $coupon->id }}" data-person="{{ $person->id }}">
                                    {{ $coupon->daily_amount }} @icon({{ $coupon->icon }}) {{ $coupon->name }} ({{ $lastHandout }})
                                </button>
                            @else
                                <button type="button" class="btn btn-primary btn-sm btn-block give-coupon" data-coupon="{{ $coupon->id }}" data-person="{{ $person->id }}" data-amount="{{ $coupon->daily_amount }}" data-min_age="{{ $coupon->min_age }}"  data-max_age="{{ $coupon->max_age }}" data-qr-code-enabled="{{ $coupon->qr_code_enabled }}">
                                    {{ $coupon->daily_amount }} @icon({{ $coupon->icon }}) {{ $coupon->name }}@if($coupon->qr_code_enabled) @icon(qrcode)@endif
                                </button>
                            @endempty
                        @endif
                    </div>
                @endif
            @empty
                <em class="pb-2 px-2">@lang('people.no_coupons_defined')</em>
            @endforelse
        </div>
    </div>
</div>
