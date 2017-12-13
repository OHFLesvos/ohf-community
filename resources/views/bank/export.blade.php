    <table>
        <thead>
            <tr>
                <th>Family Name</th>
                <th>Name</th>
                <th>Case No</th>
                <th>Medical No</th>
                <th>Registration No</th>
                <th>Section Card No</th>
                <th>Nationality</th>
                <th>Remarks</th>
                @for ($i = 1; $i <= $day; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($persons as $person)
                <tr>
                    <td>{{ $person->family_name }}</td>
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->case_no }}</td>
                    <td>{{ $person->medical_no }}</td>
                    <td>{{ $person->registration_no }}</td>
                    <td>{{ $person->section_card_no }}</td>
                    <td>{{ $person->nationality }}</td>
                    <td>{{ $person->remarks }}</td>
                    @for ($i = 1; $i <= $day; $i++)
                        <td>{{ $person->dayTransactions($year, $month, $i) }}</td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
  
