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
        <div class="col-md-2 text-right">
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
                <th style="width:60px">&nbsp;</th>
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
                    <td>
                       <a href="javascript:;" data-id="{{ $person->id }}" class="edit-person"><i class="fa fa-pencil"></i></a> &nbsp; 
                       <a href="javascript:;" data-id="{{ $person->id }}" class="delete-person"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        {!! Form::open(['route' => 'bank.store']) !!}
        <tfoot>
            <tr>
                <td>{{ Form::text('family_name', null, [ 'class' => 'form-control']) }}</td>
                <td>{{ Form::text('name', null, [ 'class' => 'form-control' ]) }}</td>
                <td>{{ Form::number('case_no', null, [ 'class' => 'form-control' ]) }}</td>
                <td>{{ Form::text('nationality', null, [ 'class' => 'form-control' ]) }}</td>
                <td>{{ Form::text('remarks', null, [ 'class' => 'form-control' ]) }}</td>
                <td>{{ Form::submit('Add', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }}</td>
            </tr>
        </tfoot>
       {!! Form::close() !!}
    </table>    

@endsection

@section('script')
    $(function(){
        $('#filter').on('change keyup', function(e){
            if (e.keyCode == 27) { 
                $(this).val('').focus();
            }
            var filter = $(this).val();
            $.post( "{{ route('bank.filter') }}", {
                "_token": "{{ csrf_token() }}",
                "filter": filter
            }, function(data) {
                var tbody = $('#results-table tbody');
                tbody.empty();
                if (data.length > 0) {
                    $.each(data, function(k, v){
                        tbody.append($('<tr>')
                            .append($('<td>').text(v.name))
                            .append($('<td>').text(v.family_name))
                            .append($('<td>').text(v.case_no))
                            .append($('<td>').text(v.nationality))
                            .append($('<td>').text(v.remarks))
                            .append($('<td>').text(''))
                        );
                    });
                } else {
                        tbody.append($('<tr>')
                            .addClass('warning')
                            .append($('<td>').text('No results').attr('colspan', 6))
                        );
                }
            })
            .fail(function() {
                //alert( "error" );
            });
       });
       $('#filter').on('focus', function(e){
           $(this).select();
       });
       $('#filter').focus();
    });
@endsection
