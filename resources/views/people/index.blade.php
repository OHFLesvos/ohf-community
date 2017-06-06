@extends('layouts.app')

@section('title', 'People')

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <span class="pull-right">
            <a href="{{ route('people.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Register</a> &nbsp;
            <a href="{{ route('people.export') }}" class="btn btn-default"><i class="fa fa-download"></i> Export</a> &nbsp;
            <a href="{{ route('people.import') }}" class="btn btn-default"><i class="fa fa-upload"></i> Import</a>
        </span>
    </div>
    <br>
    <p id="result-stats"></p>
    <table class="table table-striped table-consended table-bordered" id="results-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Family Name</th>
                <th>Case No.</th>
                <th>Nationality</th>
                <th>Languages</th>
                <th>Skills</th>
                <th>Remarks</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7">Loading, please wait...</td>
            </tr>
        </tbody>
    </table>    
    
@endsection

@section('script')
    $(function(){
       filterTable();
    });
    
    function filterTable(filter) {
        var tbody = $('#results-table tbody');
        tbody.empty();
        tbody.append($('<tr>')
            .append($('<td>')
                .text('Searching...')
                .attr('colspan', 8))
        );
        $.post( "{{ route('people.filter') }}", {
            "_token": "{{ csrf_token() }}",
            "filter": filter
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
                        .attr('colspan', 8))
                );
            }
            $('#result-stats')
                .html( data.results.length < data.total ? 'Showing <strong>' + data.results.length + '</strong> of <strong>' + data.total + '</strong> persons, please refine your search' : 'Found <strong>' + data.results.length + '</strong> persons');
        })
        .fail(function(jqXHR, textStatus) {
            tbody.empty();
            tbody.append($('<tr>')
                .addClass('danger')
                .append($('<td>')
                    .text(textStatus)
                    .attr('colspan', 8))
            );
        });
    }
    
    function writeRow(person) {
        return $('<tr>')
            .attr('id', 'person-' + person.id)
            .append($('<td>')
                .append($('<a>')
                    .attr('href', 'people/edit/' + person.id)
                    .text(person.name)
                )            
            )
            .append($('<td>')
                .append($('<a>')
                    .attr('href', 'people/edit/' + person.id)
                    .text(person.family_name)
                )
            )
            .append($('<td>').text(person.case_no))
            .append($('<td>').text(person.nationality))
            .append($('<td>').text(person.languages))
            .append($('<td>').text(person.skills))
            .append($('<td>').text(person.remarks))
            .append($('<td>').append(
                $('<a>').text()
            
            ));
    }
@endsection
