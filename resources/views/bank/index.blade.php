@extends('layouts.app')

@section('title', 'Bank')

@section('buttons')
    @can('create', App\Person::class)
        <a href="{{ route('people.create') }}" class="btn btn-primary">@icon(plus-circle)<span class="d-none d-md-inline"> Register</span></a>
    @endcan
    @component('components.context-nav')
        <li><a href="{{ route('bank.charts') }}" class="btn btn-light btn-block">@icon(line-chart) Charts</a></li>
        <li><a href="{{ route('bank.export') }}" class="btn btn-light btn-block">@icon(download) Export</a></li>
        @can('create', App\Person::class)
            <li><a href="{{ route('bank.import') }}" class="btn btn-light btn-block">@icon(upload) Import</a></li>
        @endcan
        <li><a href="{{ route('bank.settings') }}" class="btn btn-light btn-block">@icon(cogs) Settings</a></li>
    @endcomponent
@endsection

@section('content')

	<div class="row">
		<div class="col">
            <div class="input-group">
			    {{ Form::text('filter', Session::has('filter') ? session('filter') : null, [ 'id' => 'filter', 'class' => 'form-control', 'autofocus', 'placeholder' => 'Search for name, case number, medical number, registration number, section card number...' ]) }}<br>
                <span class="input-group-btn">
                    <button class="btn btn-dark" type="button" id="reset">@icon(eraser)</button>
                </span>
            </div>
		</div>
		<div class="col-md-auto pt-1">
			{{ Form::checkbox('filter-today', 1, false, [ 'id' => 'filter-today' ] ) }}
			{{ Form::label('filter-today', 'has transactions today') }}
		</div>
	</div>
    <p><small id="result-stats">Please search for a person in the field above.</small></p>
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
	<p><small class="pull-rit text-sm text-right text-muted" id="filter-status"></small></p>

@endsection

@section('script')
    var delayTimer;
    $(function(){
        $('#reset').on('click', function(e){
            $('#filter').val('').focus();
            filterTable($('#filter').val(), false);
        });

        $('#filter').on('change keyup', function(e){
            var keyCode = e.keyCode;
            $('#result-stats').html('&nbsp;');
            if (keyCode == 0 || keyCode == 8 || keyCode == 13 ||  keyCode == 27 || keyCode == 46 || (keyCode >= 48 && keyCode <= 90) || (keyCode >= 96 && keyCode <= 111)) {
                var elem = $(this);
                if (keyCode == 27) {  // ESC
                    elem.val('').focus();
                }
                if (keyCode == 13) {  // Enter
                    elem.blur();
                }
                resetTable();
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function(){
                    filterTable(elem.val(), false);
                }, 500);
            }
       });
       
       if ($.session.get('bank.filter-today')) {
            $('#filter-today').attr('checked', 'checked');
       }
       
       $('#filter-today').change(function(){
           if ($('#filter-today').is(':checked')) {
                $.session.set('bank.filter-today', 1);
                filterTable($('#filter').val(), true);
           } else {
                $.session.remove('bank.filter-today');
                filterTable($('#filter').val(), false);
           }
       });

       $('#filter').on('focus', function(e){
           $(this).select();
       });

        $('#filter').focus();
        filterTable($('#filter').val(), $.session.get('bank.filter-today'));
    });

    function resetTable() {
        $('#results-table').hide();
        $('#filter-status').html('');
        var tbody = $('#results-table tbody');
        tbody.empty();
        tbody.append($('<tr>')
            .append($('<td>')
                .text('Searching...')
                .attr('colspan', 11))
            );
    }

    function filterTable(filter, force) {
        if ( filter == '' && ! force ) {
            $('#results-table').hide();
            $('#result-stats').html('Please search for a person in the field above.');
            $('#filter-status').html('');
            return;
        }

        resetTable();
        $('#results-table').show();
        var tbody = $('#results-table tbody');

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
                            .attr('href', '{{ route('people.create') }}' + (data.register ? '?' + data.register : ''))
                            .text('Register new')
                        )
                        .attr('colspan', 11))
                );
            }
            $('#result-stats')
                .html( data.results.length < data.total ? 'Showing <strong>' + data.results.length + '</strong> of <strong>' + data.total + '</strong> persons, refine your search.' : 'Found <strong>' + data.results.length + '</strong> persons');
				
			$('#filter-status').html('Results fetched in ' + data.rendertime + ' ms');
        })
        .fail(function(jqXHR, textStatus) {
            tbody.empty();
            tbody.append($('<tr>')
                .addClass('danger')
                .append($('<td>')
                    .text(textStatus)
                    .attr('colspan', 11))
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
