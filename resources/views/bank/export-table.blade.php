<table>
    <thead>
        <tr>
            <th>@lang('people.id')</th>
            <th>@lang('people.family_name')</th>
            <th>@lang('people.name')</th>
            <th>@lang('people.date_of_birth')</th>
            <th>@lang('people.age')</th>
            <th>@lang('people.nationality')</th>
            <th>@lang('people.police_number')</th>
            <th>@lang('people.case_number')</th>
            <th>@lang('people.medical_number')</th>
            <th>@lang('people.registration_number')</th>
            <th>@lang('people.section_card_number')</th>
            <th>@lang('people.temporary_number')</th>
            @foreach($couponTypes as $coupon)
                <th>{{ $coupon->name }}</th>
            @endforeach
            <th>@lang('people.remarks')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($persons as $person)
            <tr>
                <td>{{ $person->id }}</td>
                <td>{{ $person->family_name }}</td>
                <td>{{ $person->name }}</td>
                <td>{{ $person->date_of_birth }}</td>
                <td>{{ $person->age }}</td>
                <td>{{ $person->nationality }}</td>
                <td>{{ $person->police_no }}</td>
                <td>{{ $person->case_no }}</td>
                <td>{{ $person->medical_no }}</td>
                <td>{{ $person->registration_no }}</td>
                <td>{{ $person->section_card_no }}</td>
                <td>{{ $person->temp_no }}</td>
                @foreach($couponTypes as $coupon)
                    <td style="text-align: center">
                        @if($person->eligibleForCoupon($coupon))
                            @php
                                $lastHandout = $person->lastCouponHandout($coupon);
                            @endphp
                            @isset($lastHandout)
                                {{ $lastHandout }}
                            @endisset
                        @else
                            x
                        @endif
                    </td>
                @endforeach
                <td>{{ $person->remarks }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
  
