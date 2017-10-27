@extends('layouts.app')

@section('title', 'Bank')

@section('buttons')
    <a href="{{ route('bank.register') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class=" d-none d-md-inline"> Register</span></a>
    <a href="{{ route('bank.charts') }}" class="btn btn-secondary"><i class="fa fa-line-chart"></i><span class=" d-none d-md-inline">  Charts</span></a>
    <a href="{{ route('bank.settings') }}" class="btn btn-secondary"><i class="fa fa-cogs"></i><span class=" d-none d-md-inline">  Settings</span></a>
    <a href="{{ route('bank.export') }}" class="btn btn-secondary"><i class="fa fa-download"></i><span class=" d-none d-md-inline">  Export</span></a>
    <a href="{{ route('bank.import') }}" class="btn btn-secondary"><i class="fa fa-upload"></i><span class=" d-none d-md-inline">  Import</span></a>
@endsection

@section('content')

	<div class="row">
		<div class="col">
			{{ Form::text('filter', Session::has('filter') ? session('filter') : null, [ 'id' => 'filter', 'class' => 'form-control', 'placeholder' => 'Search for name or case number.' ]) }}<br>
		</div>
		<div class="col-md-auto">
			{{ Form::checkbox('filter-today', 1, false, [ 'id' => 'filter-today' ] ) }}
			{{ Form::label('filter-today', 'has transactions today') }}
		</div>
	</div>

    <p id="result-stats">Loading...</p>
    <table class="table table-sm table-striped table-bordered table-hover" id="results-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Family Name</th>
                <th>Case No.</th>
                <th>Nationality</th>
                <th>Remarks</th>
				<th style="width: 170px">Boutique</th>
                <th style="width: 100px;">Yesterday</th>
                <th style="width: 100px;">Today</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8">Loading, please wait...</td>
            </tr>
        </tbody>
    </table>
	<small class="pull-rit text-sm text-right text-muted" id="filter-status"></small>

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
            }, 500);
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
		$('#filter-status').html('');
        var tbody = $('#results-table tbody');
        tbody.empty();
        tbody.append($('<tr>')
            .append($('<td>')
                .text('Searching...')
                .attr('colspan', 8))
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
                        .attr('colspan', 8))
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
								.attr('max', {{ $single_transaction_max_amount }})
                                .attr('value', person.today)
                                .addClass('form-control form-control-sm')
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
					.attr('max', {{ $single_transaction_max_amount }})
                    .attr('value', 0)
                    .addClass('form-control form-control-sm')
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
            .addClass(person.today > 0 ? 'table-success' : null)
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
                        .attr('href', 'bank/transactions/' + person.id)
                        .append($('<i>')
                            .addClass('fa fa-search')
                        )
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
			$('tr#person-' + personId).replaceWith(writeRow(data));
            $('#filter').select();
        })
        .fail(function(jqXHR, textStatus) {
            alert(textStatus + ': ' + jqXHR.responseJSON);
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
