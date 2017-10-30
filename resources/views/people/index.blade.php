@extends('layouts.app')

@section('title', 'People')

@section('buttons')
    @can('create', App\Person::class)
        <a href="{{ route('people.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Register</a>
    @endcan
    <a href="{{ route('people.charts') }}" class="btn btn-secondary"><i class="fa fa-line-chart"></i> Charts</a>
    @can('list', App\Person::class)
        <a href="{{ route('people.export') }}" class="btn btn-secondary"><i class="fa fa-download"></i> Export</a>
    @endcan
    @can('create', App\Person::class)
        <a href="{{ route('people.import') }}" class="btn btn-secondary"><i class="fa fa-upload"></i> Import</a>
    @endcan
@endsection

@section('content')

    <p id="result-stats">Loading...</p>
    <table class="table table-sm table-striped table-bordered table-hover" id="results-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Family Name</th>
                <th>Case No.</th>
                <th>Nationality</th>
                <th>Languages</th>
                <th>Skills</th>
                <th>Remarks</th>
            </tr>
            <tr id="filter">
                <th>{{ Form::text('name', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('family_name', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('case_no', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('nationality', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('languages', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('skills', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('remarks', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7">Loading, please wait...</td>
            </tr>
        </tbody>
    </table>   
	<small class="pull-rit text-sm text-right text-muted" id="filter-status"></small>

@endsection

@section('script')
    var delayTimer;

    $(function(){
        $('#filter input').on('change keyup', function(e){
            var keyCode = e.keyCode;
            var elem = $(this);
            clearTimeout(delayTimer);
            delayTimer = setTimeout(function(){
                if (keyCode == 27) {  // ESC
                    elem.val('').focus();
                }
                filterTable();
            }, 1000);
        });

        filterTable();
    });
    
    function filterTable() {
		$('#filter-status').html('');
        var tbody = $('#results-table tbody');
        tbody.empty();
        tbody.append($('<tr>')
            .append($('<td>')
                .text('Searching...')
                .attr('colspan', 7))
        );
        $.post( "{{ route('people.filter') }}", {
            "_token": "{{ csrf_token() }}",
            "name": $('#filter input[name="name"]').val(),
            "family_name": $('#filter input[name="family_name"]').val(),
            "case_no": $('#filter input[name="case_no"]').val(),
            "nationality": $('#filter input[name="nationality"]').val(),
            "languages": $('#filter input[name="languages"]').val(),
            "skills": $('#filter input[name="skills"]').val(),
            "remarks": $('#filter input[name="remarks"]').val(),
        }, function(data) {
            tbody.empty();
            if (data.results.length > 0) {
                $.each(data.results, function(k, v){
                    tbody.append(writeRow(v));
                });
            } else {
                tbody.append($('<tr>')
                    .addClass('warning')
                    .append($('<td>')
                        .text('No results')
                        .attr('colspan', 7))
                );
            }
            $('#result-stats')
                .html( data.results.length < data.total ? 'Showing <strong>' + data.results.length + '</strong> of <strong>' + data.total + '</strong> persons, please refine your search.' : 'Found <strong>' + data.results.length + '</strong> persons');

			$('#filter-status').html('Results fetched in ' + data.rendertime + ' ms');
        })
        .fail(function(jqXHR, textStatus) {
            tbody.empty();
            tbody.append($('<tr>')
                .addClass('danger')
                .append($('<td>')
                    .text(textStatus)
                    .attr('colspan', 7))
            );
        });
    }
    
    function writeRow(person) {
        return $('<tr>')
            .attr('id', 'person-' + person.id)
            .append($('<td>')
                .append($('<a>')
                    .attr('href', 'people/' + person.id)
                    .text(person.name)
                )            
            )
            .append($('<td>')
                .append($('<a>')
                    .attr('href', 'people/' + person.id)
                    .text(person.family_name)
                )
            )
            .append($('<td>').text(person.case_no))
            .append($('<td>').text(person.nationality))
            .append($('<td>').text(person.languages))
            .append($('<td>').text(person.skills))
            .append($('<td>').text(person.remarks));
    }
@endsection
