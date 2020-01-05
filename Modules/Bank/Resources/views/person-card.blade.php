@php
    $vue_person = [
        'full_name' => $person->full_name,
        'gender' => $person->gender,
        'date_of_birth' => $person->date_of_birth,
        'age' => $person->age,
        'nationality' => $person->nationality,
        'frequent_visitor' => $person->frequentVisitor,
        'can_view' => Auth::user()->can('view', $person),
        'can_update' => Auth::user()->can('update', $person),
        'show_url' => route('bank.people.show', $person),
        'edit_url' => route('bank.people.edit', $person),
        'gender_update_url' => route('api.people.setGender', $person),
        'date_of_birth_update_url' => route('api.people.setDateOfBirth', $person),
        'nationality_update_url' => route('api.people.setNationality', $person),
        'remarks_update_url' => route('api.people.updateRemarks', $person),
        'card_no' => $person->card_no,
        'register_card_url' => route('api.people.registerCard', $person),
        'is_helper' => is_module_enabled('Helpers') && optional($person->helper)->isActive,
        'can_view_helper' => $person->helper != null && Auth::user()->can('view', $person->helper),
        'show_helper_url' => $person->helper != null ? route('people.helpers.show', $person->helper) : null,
        'police_no' => $person->police_no,
        'police_no_formatted' => $person->police_no_formatted,
        'case_no_hash' => $person->case_no_hash,
        'remarks' => $person->remarks,
        'has_overdue_book_lendings' => is_module_enabled('Library') && $person->hasOverdueBookLendings,
        'can_operate_library' => Gate::allows('operate-library'),
        'library_lending_person_url' => is_module_enabled('Library') ? route('library.lending.person', $person) : null,
        'coupon_types' => collect($couponTypes)
            ->map(function($coupon) use($person) {
                $returning_possible = optional($person->couponHandouts()
                    ->where('coupon_type_id', $coupon->id)
                    ->orderBy('date', 'desc')
                    ->first())->isReturningPossible;
                return [
                    'id' => $coupon->id,
                    'daily_amount' => $coupon->daily_amount,
                    'icon' => $coupon->icon,
                    'name' => $coupon->name,
                    'min_age' => $coupon->min_age,
                    'max_age' => $coupon->max_age,
                    'qr_code_enabled' => $coupon->qr_code_enabled,
                    'person_eligible_for_coupon' => $person->eligibleForCoupon($coupon),
                    'last_handout' => $person->canHandoutCoupon($coupon),
                    'handout_url' => route('bank.handoutCoupon', [$person, $coupon]),
                    'returning_possible' => $returning_possible,
                ];
            })
            ->toArray()
    ];
    $lang_arr = lang_arr([
        'app.register',
        'people::people.police_number',
        'people::people.case_number',
        'app.yes',
        'library::library.library',
        'people::people.no_coupons_defined',
        'helpers::helpers.helper',
        'app.undo',
        'people::people.remarks',
        'people::people.click_to_add_remarks',
    ]);
@endphp
<bank-person-card
    :person='@json($vue_person)'
    :lang='@json($lang_arr)'
    {{-- :disabled="true" --}}
    {{-- @change="reload" TODO --}}
></bank-person-card>