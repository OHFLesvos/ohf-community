    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Family Name</th>
                <th>Case No</th>
                <th>Medical No</th>
                <th>Registration No</th>
                <th>Section Card No</th>
                <th>Nationality</th>
                <th>Languages</th>
                <th>Skills</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($persons as $person)
                <tr>
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->family_name }}</td>
                    <td>{{ $person->case_no }}</td>
                    <td>{{ $person->medical_no }}</td>
                    <td>{{ $person->registration_no }}</td>
                    <td>{{ $person->section_card_no }}</td>
                    <td>{{ $person->nationality }}</td>
                    <td>{{ $person->languages }}</td>
                    <td>{{ $person->skills }}</td>
                    <td>{{ $person->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
  
