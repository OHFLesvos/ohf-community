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
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 filter-container ">
            {{ Form::text('filter', Session::has('filter') ? session('filter') : null, [ 'id' => 'filter', 'class' => 'form-control', 'placeholder' => 'Search for name or case number.' ]) }}<br>
            {{ Form::checkbox('filter-today', 1, false, [ 'id' => 'filter-today' ] ) }}
            {{ Form::label('filter-today', 'has transactions today') }}
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('bank.register') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Register</a> &nbsp;
            <a href="{{ route('bank.charts') }}" class="btn btn-default"><i class="fa fa-line-chart"></i> Charts</a> &nbsp;
            <a href="{{ route('bank.export') }}" class="btn btn-default"><i class="fa fa-download"></i> Export</a> &nbsp;
            <a href="{{ route('bank.import') }}" class="btn btn-default"><i class="fa fa-upload"></i> Import</a>
        </div>
    </div>
    <br>
    <p id="result-stats">Loading...</p>
    <table class="table table-striped table-condensed table-bordered" id="results-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Family Name</th>
                <th>Case No.</th>
                <th>Nationality</th>
                <th>Remarks</th>
				<th style="width: 150px">Boutique</th>
                <th style="width: 100px;">Yesterday</th>
                <th style="width: 100px;">Today</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7">Loading, please wait...</td>
            </tr>
        </tbody>
    </table>    
    
    <div class="modal" tabindex="-1" role="dialog" id="myModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Transactions</h4>
          </div>
          <div class="modal-body">
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
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
                if (keyCode == 0 || keyCode == 8 || keyCode == 27 || keyCode == 46 || (keyCode >= 48 && keyCode <= 90) || (keyCode >= 96 && keyCode <= 111)) {
                    filterTable(elem.val());
                }
            }, 1000);
       });
       
       if ($.session.get('bank.filter-today')) {
            $('#filter-today').attr('checked', 'checked');
       }
       
       $('#filter-today').change(function(){
           if ($('#filter-today').is(':checked')) {
               $.session.set('bank.filter-today', 1);
           } else {
               $.session.remove('bank.filter-today');
           }
           filterTable($('#filter').val());
       });

       $('#filter').on('focus', function(e){
           $(this).select();
       });

       $('#filter').focus();
       filterTable($('#filter').val());
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
            "filter": filter,
            "today": $('#filter-today').is(':checked') ? 1 : 0
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
                        .append(' &nbsp; ')
                        .append($('<a>')
                            .attr('href', '{{ route('bank.register') }}' + (data.register ? '?' + data.register : ''))
                            .text('Register new')
                        )
                        .attr('colspan', 7))
                );
            }
            $('#result-stats')
                .html( data.results.length < data.total ? 'Showing <strong>' + data.results.length + '</strong> of <strong>' + data.total + '</strong> persons, please refine your search.' : 'Found <strong>' + data.results.length + '</strong> persons');
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
                                .addClass('form-control input-sm')
                                .on('focus', function(){
                                    $(this).select();
                                })
                                .on('keypress', function(e){
                                    if (e.keyCode == 13) { // Enter
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
                    .addClass('form-control input-sm')
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
            .addClass(person.today > 0 ? 'success' : null)
            .append($('<td>')
                .text(person.name)
                .append(' &nbsp; ')
                .append($('<a>')
                    .attr('href', 'bank/editPerson/' + person.id)
                    .append($('<i>')
                        .addClass('fa fa-pencil')
                    )
                )            
            )
            .append($('<td>').text(person.family_name))
            .append($('<td>').text(person.case_no))
            .append($('<td>').text(person.nationality))
            .append($('<td>').text(person.remarks))
			// Boutique coupon
			.append($('<td>').html(function(){ 
				if (person.boutique_coupon) {
					return person.boutique_coupon;
				}
				return $('<a>')
					.attr('href', 'javascript:;')
					.text('Give coupon')
					.on('click', function(){
						if (confirm('Give coupon to ' + person.name + ' ' + person.family_name + '?')) {
							$.post( "{{ route('bank.giveBoutiqueCoupon') }}", {
								"_token": "{{ csrf_token() }}",
								"person_id": person.id
							}, function(data) {
								updatePerson(person.id);
								$('#filter').select();
							})
							.fail(function(jqXHR, textStatus) {
								alert(extStatus);
							});
						}
					});
			}))
            .append(
                $('<td>')
                    .html(person.yesterday > 0 ? '<strong>' + person.yesterday + '</strong>' : 0)
                    .append(' &nbsp; ')
                    .append($('<a>')
                        .attr('href', 'javascript:;')
                        .append($('<i>')
                            .addClass('fa fa-search')
                        )
                        .on('click', function(){
                            $.get( 'bank/transactions/' + person.id, function(data) {
                                $('#myModal .modal-title').text('Transactions of ' + person.name + ' ' + person.family_name);
                                $('#myModal .modal-body').empty();
                                var tbody = $('<tbody>');
                                if (data.length > 0) {
                                    $.each(data, function(k, v){
                                        tbody.append($('<tr>')
                                            .append($('<td>')
                                                .text(v.created_at)
                                            )                   
                                            .append($('<td>')
                                                .text(v.value)
                                            )                   
                                        );
                                    })
                                    $('#myModal .modal-body').append(
                                        $('<table>')
                                                .addClass('table table-striped table-consended')
                                                .append(tbody)
                                    );
                                } else {
                                    $('#myModal .modal-body').append('No transactions found.');
                                }
                                $('#myModal').modal();
                            })
                            .fail(function(jqXHR, textStatus) {
                                alert(textStatus);
                            });
                        })
                    )
            )
            .append(today);
    }
    
    function storeTransaction(personId, value) {
        $.post( "{{ route('bank.storeTransaction') }}", {
            "_token": "{{ csrf_token() }}",
            "person_id": personId,
            "value": value
        }, function(data) {
            updatePerson(personId);
            $('#filter').select();
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
