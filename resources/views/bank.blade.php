@extends('layouts.app')

@section('title', 'Bank')

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

    <div class="row">
        <div class="col-md-10">
            {{ Form::text('filter', null, [ 'id' => 'filter', 'class' => 'form-control', 'placeholder' => 'Search for name or case number.' ]) }}
        </div>
        <div class="col-md-1">
            <span id="result-stats"></span>
        </div>
        <div class="col-md-1 text-right">
            <a href="{{ route('bank.export') }}" class="btn btn-primary"><i class="fa fa-download"></i> Export</a>
        </div>
    </div>
    <br>
    <table class="table table-striped table-consended table-bordered" id="results-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Family name</th>
                <th>Case No.</th>
                <th>Nationality</th>
                <th>Remarks</th>
                <th style="width: 100px;">Yesterday</th>
                <th style="width: 100px;">Today</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7">Loading, please wait...</td>
            </tr>
        </tbody>
        {!! Form::open(['route' => 'bank.store']) !!}
        <tfoot>
            <tr>
                <td>{{ Form::text('family_name', null, [ 'class' => 'form-control']) }}</td>
                <td>{{ Form::text('name', null, [ 'class' => 'form-control' ]) }}</td>
                <td>{{ Form::number('case_no', null, [ 'class' => 'form-control' ]) }}</td>
                <td>{{ Form::text('nationality', null, [ 'class' => 'form-control' ]) }}</td>
                <td>{{ Form::text('remarks', null, [ 'class' => 'form-control' ]) }}</td>
                <td colspan="2">{{ Form::submit('Add', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }}</td>
            </tr>
        </tfoot>
       {!! Form::close() !!}
    </table>    

@endsection

@section('script')
    var delayTimer;
    $(function(){
        $('#filter').on('change keyup', function(e){
            var keyCode = e.keyCode;
            var elem = $(this);
            clearTimeout(delayTimer);
            delayTimer = setTimeout(function(){
                if (keyCode == 27) {  // ESC
                    elem.val('').focus();
                }
                filterTable(elem.val());
            }, 1000);
       });

       $('#filter').on('focus', function(e){
           $(this).select();
       });

       $('#filter').focus();
       filterTable('');
    });
    
    function filterTable(filter) {
        var tbody = $('#results-table tbody');
        tbody.empty();
        tbody.append($('<tr>')
            .append($('<td>')
                .text('Searching...')
                .attr('colspan', 7))
        );
        $.post( "{{ route('bank.filter') }}", {
            "_token": "{{ csrf_token() }}",
            "filter": filter
        }, function(data) {
            tbody.empty();
            if (data.length > 0) {
                $.each(data, function(k, v){
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
                .text(data.length + ' found');
            // $('[data-toggle="tooltip"]').tooltip();
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
        var today = $('<td>');
        if (person.today > 0) {
            today.text(person.today)
                .append(' &nbsp; ')
                .append($('<a>')
                    .attr('href', 'javascript:;')
                    .append($('<i>')
                        .addClass('fa fa-pencil')
                    )
                    .on('click', function(){
                        var transactionInput = $('<input>')
                                .attr('type', 'number')
                                .attr('min', 0)
                                .attr('value', person.today)
                                .addClass('form-control')
                                .on('focus', function(){
                                    $(this).select();
                                })
                                .on('keypress', function(e){
                                    if (e.keyCode == 13 && $(this).val() > 0) { // Enter
                                        storeTransaction(person.id, $(this).val() - person.today);
                                    } 
                                });
                        today.empty();
                        today.append(transactionInput);
                        transactionInput.focus();
                    })
                );                
        } else {
            today.append($('<input>')
                    .attr('type', 'number')
                    .attr('min', 0)
                    .attr('value', 0)
                    .addClass('form-control')
                    .on('focus', function(){
                        $(this).select();
                    })
                    .on('keypress', function(e){
                        if (e.keyCode == 13 && $(this).val() > 0) { // Enter
                            storeTransaction(person.id, $(this).val());
                        } 
                    })
                );
        }
        return $('<tr>')
            .attr('id', 'person-' + person.id)
            .append($('<td>').text(person.name))
            .append($('<td>').text(person.family_name))
            .append($('<td>').text(person.case_no))
            .append($('<td>').text(person.nationality))
            .append($('<td>').text(person.remarks))
            .append($('<td>').text(person.yesterday))
            .append(today);
    }
    
    function storeTransaction(personId, value) {
        $.post( "{{ route('bank.storeTransaction') }}", {
            "_token": "{{ csrf_token() }}",
            "person_id": personId,
            "value": value
        }, function(data) {
            updatePerson(personId);
        })
        .fail(function(jqXHR, textStatus) {
            alert(extStatus);
        });
    }
    
    function updatePerson(personId) {
        $.get( 'bank/person/' + personId, function(data) {
            $('tr#person-' + personId).replaceWith(writeRow(data));
        })
        .fail(function(jqXHR, textStatus) {
            alert(textStatus);
        });
    }
@endsection
