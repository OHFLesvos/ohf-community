@extends('layouts.app')

@section('title', 'Bank')

@section('content')

	<div class="input-group">
		{{ Form::text('filter', Session::has('filter') ? session('filter') : null, [ 'id' => 'filter', 'class' => 'form-control', 'autofocus', 'placeholder' => 'Search for name, case number, medical number, registration number, section card number...' ]) }}
		<span class="input-group-addon" id="filter-reset">
			@icon(eraser)
		</span>
	</div>

    <p><small id="filter-status"></small></p>
	
	<p id="hints">
		Filter: 
		<a href="javascript:;" class="add-filter" data-filter="today: ">Show people having transactions today</a>
	</p>

    <table class="table table-sm table-striped table-bordered table-hover table-responsive-md" id="results-table" style="display: none;">
        <thead>
            <tr>
                <th>Name</th>
                <th class="text-nowrap">Family Name</th>
                <th class="text-nowrap">Case No.</th>
                <th class="text-nowrap">Med No.</th>
                <th class="text-nowrap">Reg No.</th>
                <th class="text-nowrap">Sec Card No.</th>
                <th>Nationality</th>
                <th>Remarks</th>
				<th style="width: 170px">Boutique</th>
                <th style="width: 100px;">Yesterday</th>
                <th style="width: 100px;">Today</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

	<div id="result-alert" style="display: none;"></div>
	
@endsection

@section('script')
	var delayTimer;
	var filterField = $( '#filter' );
	var statusContainer =  $( '#filter-status' );
	var table = $('#results-table');
	var alert = $('#result-alert');
	var filterReset = $('#filter-reset');
	var lastFilterValue = "";

    $(function(){
		
		filterField.on('change paste propertychange input', function(evt){
			//console.log( 'EVENT '  + evt.type );
			
			if (evt.type == 'input' || evt.type == 'propertychange') {
				clearTimeout(delayTimer);
				delayTimer = setTimeout(function(){
					//console.log('DELAY');
					applyFilter(filterField.val());
				}, 250);
			} else {
				applyFilter(filterField.val());
			}
		});

		filterField.on('keydown', function(evt){
			var isEscape = false;
			if ("key" in evt) {
				isEscape = (evt.key == "Escape" || evt.key == "Esc");
			} else {
				isEscape = (evt.keyCode == 27);
			}
			if (isEscape) {
				//console.log( 'ESCAPE '  + evt.type );
				resetFilter();
			}
		});
		
		filterReset.on('click', function(){
			resetFilter();
		});
		
		$('.add-filter').on('click', function(){
			var filterVal = $(this).attr('data-filter');
			filterField.focus().val( filterVal + filterField.val() ).change();
		});
		
		filterField.select().change();
    });
	
	function resetFilter() {
		// console.log( 'RESET filter' );
		filterField.val('').change().focus();
		resetAlert();
		resetStatus();
		$.post( "{{ route('bank.resetFilter') }}", {
			"_token": "{{ csrf_token() }}"
		});
	}
	
	function applyFilter(value) {
		if (lastFilterValue == value) {
			return;
		}
		// console.log('APPLY "' + value + '"');
		
		if (value != '') {
			filterReset.addClass('bg-primary text-light');
		} else {
			filterReset.removeClass('bg-primary text-light');
		}
		
		var searchValue = value.trim();
		if (searchValue != '') {
			filterTable(searchValue);
		} else {
			table.hide();
			resetAlert();
			resetStatus();
		}
		lastFilterValue = value;
	}
	
	function showStatus(msg) {
		statusContainer.html(msg);
	}

	function resetStatus() {
		statusContainer.html('');
	}

	function showAlert(msg, type) {
		var alert = $('#result-alert');
		alert.html(msg);
		alert.addClass('alert alert-' + type);
		var icon = type == 'danger' ? 'warning' : 'info-circle';
		alert.prepend($('<i>').addClass('fa fa-' + icon).append('&nbsp;'));
		alert.show();
	}

	function resetAlert() {
		alert.hide();
		alert.removeClass();
	}

    function filterTable(filter) {
		showStatus('Searching...'); //  for \'' + filter + '\'
        $.post( "{{ route('bank.filter') }}", {
            "_token": "{{ csrf_token() }}",
            "filter": filter
        }, function(data) {
			var tbody = table.children('tbody');
            if (data.results.length > 0) {
				tbody.empty();
                $.each(data.results, function(k, v){
                    tbody.append(writeRow(v));
                });
				table.show();
				resetAlert();
				showStatus(data.results.length < data.total ? 'Showing <strong>' + data.results.length + '</strong> of <strong>' + data.total + '</strong> persons, refine your search.' : 'Found <strong>' + data.results.length + '</strong> persons.');
            } else {
				table.hide();
				resetStatus();
				var msg = $('<span>').text('No results. ')
					.append($('<a>')
						.attr('href', '{{ route('people.create') }}' + (data.register ? '?' + data.register : ''))
						.append('Register new person'));
				showAlert(msg, 'info');
            }
		})
        .fail(function(jqXHR, textStatus, error) {
			table.hide();
			resetStatus();
			showAlert(textStatus + ": " + error, 'danger');
			console.log("Error: " + textStatus + " " + jqXHR.responseText);
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
            .append($('<td>').text(person.medical_no))
            .append($('<td>').text(person.registration_no))
            .append($('<td>').text(person.section_card_no))
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
