@extends('layouts.app')

@section('title', 'Bank')

@section('content')

	<div class="input-group">
		{{ Form::search('filter', Session::has('filter') ? session('filter') : null, [ 'id' => 'filter', 'class' => 'form-control', 'autofocus', 'placeholder' => 'Search for name, case number, medical number, registration number, section card number...' ]) }}
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
	var filterField = $('#filter');
	var statusContainer =  $('#filter-status');
	var table = $('#results-table');
	var alertContainer = $('#result-alert');
	var filterReset = $('#filter-reset');
	var addFilterElem = $('.add-filter');
	
	var csrfToken = '{{ csrf_token() }}';
	var filterUrl = '{{ route('bank.filter') }}';
	var resetFilterUrl = '{{ route('bank.resetFilter') }}';
	var createNewRecordUrl = '{{ route('people.create') }}';
	var giveBouqiqueCouponUrl = '{{ route('bank.giveBoutiqueCoupon') }}';
	var storeTransactionUrl = '{{ route('bank.storeTransaction') }}';

	var transactionMaxAmount = {{ $single_transaction_max_amount }};
@endsection

@section('footer')
	<script src="{{asset('js/bank.js')}}?v={{ $app_version }}"></script>
@endsection
