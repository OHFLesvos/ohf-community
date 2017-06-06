    <table class="table table-striped table-consended table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Family name</th>
                <th>Case No.</th>
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
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->family_name }}</td>
                    <td>{{ $person->case_no }}</td>
                    <td>{{ $person->nationality }}</td>
                    <td>{{ $person->remarks }}</td>
                    @for ($i = 1; $i <= $day; $i++)
                        <td>{{ $person->dayTransactions($year, $month, $i) }}</td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
  
