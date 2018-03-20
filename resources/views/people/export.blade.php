    <table>
        <thead>
            <tr>
                <th>@lang('people.family_name')</th>
                <th>@lang('people.name')</th>
                <th>@lang('people.police_no')</th>
                <th>@lang('people.case_no')</th>
                <th>@lang('people.med_no')</th>
                <th>@lang('people.reg_no')</th>
                <th>@lang('people.sec_card_no')</th>
                <th>@lang('people.temp_no')</th>
                <th>@lang('people.nationality')</th>
                <th>@lang('people.languages')</th>
                <th>@lang('people.skills')</th>
                <th>@lang('people.remarks')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($persons as $person)
                <tr>
                    <td>{{ $person->family_name }}</td>
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->police_no }}</td>
                    <td>{{ $person->case_no }}</td>
                    <td>{{ $person->medical_no }}</td>
                    <td>{{ $person->registration_no }}</td>
                    <td>{{ $person->section_card_no }}</td>
                    <td>{{ $person->temp_no }}</td>
                    <td>{{ $person->nationality }}</td>
                    <td>{{ $person->languages }}</td>
                    <td>{{ $person->skills }}</td>
                    <td>{{ $person->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
  
