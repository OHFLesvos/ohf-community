<div class="card @if(isset($bottom_margin))mb-{{ $bottom_margin }}@else mb-4 @endif bg-light @isset($border)border-{{ $border }}@endisset">
    @php
        $frequentVisitor = $person->frequentVisitor;
    @endphp
    <div class="card-header p-2" @if($frequentVisitor) style="background:lightgoldenrodyellow;" @endif >
        <div class="form-row">
            <div class="col">
                @if(is_module_enabled('Helpers') && optional($person->helper)->isActive)
                    @can('view', $person->helper)
                        <strong>
                            <a href="{{ route('people.helpers.show', $person->helper) }}" class="text-warning">{{ strtoupper(__('helpers::helpers.helper')) }}</a>
                        </strong>
                    @else
                        <strong class="text-warning">
                            {{ strtoupper(__('helpers::helpers.helper')) }}
                        </strong>
                    @endcan
                @endif
                @can('view', $person)
                    <a href="{{ route('bank.people.show', $person) }}" alt="View"><strong class="mark-text">{{ $person->full_name }}</strong></a>
                @else
                    <strong class="mark-text">{{ $person->full_name }}</strong>
                @endcan
                <gender-selector
                    update-url="{{ route('api.people.setGender', $person) }}"
                    value="{{ $person->gender }}"
                    @can('update', $person)can-update @endcan
                ></gender-selector>
                <span class="form-inline d-inline">
                    @if(isset($person->date_of_birth))
                        {{ $person->date_of_birth }} (age {{ $person->age }})
                    @else
                        @can('update', $person)
                            <button class="btn btn-warning btn-sm choose-date-of-birth" data-url="{{ route('api.people.setDateOfBirth', $person) }}" title="Set date of birth">@icon(calendar-plus)</button>
                        @endcan
                    @endif
                </span>
                <span class="form-inline d-inline">
                    @if(isset($person->nationality))
                        {{ $person->nationality }}
                    @else
                        @can('update', $person)
                            <button class="btn btn-warning btn-sm choose-nationality" data-url="{{ route('api.people.setNationality', $person) }}" title="Set nationality">@icon(globe)</button>
                        @endcan
                @endif
                </span>
                {{-- @icon(id-card) {{ $person->public_id }} --}}
                @if($frequentVisitor)
                    <span class="text-warning" title="Frequent visitor">@icon(star)</span>
                @endif
                @can('update', $person)
                    <a href="{{ route('bank.people.edit', $person) }}" title="Edit">@icon(edit)</a>
                @endcan
            </div>
            <div class="col-auto">
                @can('update', $person)
                    @icon(id-card)
                    <a href="javascript:;" class="register-card" data-url="{{ route('api.people.registerCard', $person) }}" data-card="{{ $person->card_no }}">
                        @if(isset($person->card_no))
                            <strong>{{ substr($person->card_no, 0, 7) }}</strong>
                        @else
                            @lang('app.register')
                        @endif
                    </a>
                @else
                    @if(isset($person->card_no))
                        @icon(id-card)
                        <strong>{{ substr($person->card_no, 0, 7) }}</strong>
                    @endif
                @endcan
            </div>
        </div>
    </div>
    @if (isset($person->police_no) || isset($person->case_no_hash) || isset($person->remarks) || $person->hasOverdueBookLendings)
        <div class="card-body p-2">
            @if(isset($person->police_no))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people::people.police_number'):</small>
                    <span class="pr-2 mark-text">{{ $person->police_no_formatted }}</span>
                </span>
            @endif
            @if(isset($person->case_no_hash))
                <span class="d-block d-sm-inline">
                    <small class="text-muted">@lang('people::people.case_number'):</small>
                    <span class="pr-2">@lang('app.yes')</span>
                </span>
            @endif
            @if(isset($person->remarks))
                <div>
                    <em class="text-info">{{ $person->remarks }}</em>
                </div>
            @endif
            @if(is_module_enabled('Library') && $person->hasOverdueBookLendings)
                <div>
                    <em class="text-danger">Needs to bring back book(s) to the
                    @can('operate-library')
                        <a href="{{ route('library.lending.person', $person) }}">@lang('library::library.library')</a>
                    @else
                        @lang('library::library.library')
                    @endcan
                    </em>
                </div>
            @endif
        </div>
    @endif
    <div class="card-body p-0 px-2 pt-2">
        <div class="form-row">
            @forelse($couponTypes as $coupon)
                @if($person->eligibleForCoupon($coupon))
                    <div class="col-sm-auto mb-2">
                        @if($person->eligibleForCoupon($coupon))
                            @php
                                $lastHandout = $person->canHandoutCoupon($coupon);
                            @endphp
                            @isset($lastHandout)
                                <button type="button" class="btn btn-secondary btn-sm btn-block" disabled data-url="{{ route('bank.handoutCoupon', [$person, $coupon]) }}">
                                    {{ $coupon->daily_amount }} @icon({{ $coupon->icon }}) {{ $coupon->name }} ({{ $lastHandout }})
                                </button>
                            @else
                                <button type="button" class="btn btn-primary btn-sm btn-block give-coupon" data-url="{{ route('bank.handoutCoupon', [$person, $coupon]) }}" data-amount="{{ $coupon->daily_amount }}" data-min_age="{{ $coupon->min_age }}"  data-max_age="{{ $coupon->max_age }}" data-qr-code-enabled="{{ $coupon->qr_code_enabled }}">
                                    {{ $coupon->daily_amount }} @icon({{ $coupon->icon }}) {{ $coupon->name }}@if($coupon->qr_code_enabled) @icon(qrcode)@endif
                                </button>
                            @endempty
                        @endif
                    </div>
                @endif
            @empty
                <em class="pb-2 px-2">@lang('people::people.no_coupons_defined')</em>
            @endforelse
        </div>
    </div>
</div>
