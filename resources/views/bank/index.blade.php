@extends('layouts.app')

@section('title', 'Bank')

@section('content')

	<div class="input-group">
        {{ Form::search('filter', Session::has('filter') ? session('filter') : null, [ 'id' => 'filter', 'class' => 'form-control', 'autofocus', 'placeholder' => 'Search for name, case number, medical number, registration number, section card number...' ]) }}
        <div class="input-group-append">
            <button class="btn btn-secondary" disabled type="button" id="filter-reset">@icon(eraser)</button> 
        </div>
	</div>

	<p class="mt-2">
		Filter: 
		<a href="javascript:;" class="add-filter" data-filter="today: ">has transactions today</a>
	</p>

    <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-hover" id="results-table" style="display: none;">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-nowrap">Family Name</th>
                    <th>Name</th>
                    <th class="text-nowrap">Police No.</th>
                    <th class="text-nowrap">Case No.</th>
                    <th class="text-nowrap">Med No.</th>
                    <th class="text-nowrap">Reg No.</th>
                    <th class="text-nowrap">Sec Card No.</th>
                    <th class="text-nowrap">Temp No.</th>
                    <th>Nationality</th>
                    <th>Remarks</th>
                    <th style="width: 170px">Boutique</th>
                    <th style="width: 170px">Diapers</th>
                    {{--  <th style="width: 100px;">Yesterday</th>  --}}
                    <th style="width: 100px;">Drachma</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <p><small id="filter-status"></small></p>

    <div class="row">
        <div class="col-auto align-items-center">
            <ul class="pagination pagination-sm" id="paginator"></ul>
        </div>
        <div class="col align-items-center"><small id="paginator-info"></small></div>
    </div>

	<div id="result-alert" style="display: none;"></div>

    <div id="stats" class="text-center lead my-5" style="display: none"></div>

@endsection

@section('script')
	var filterField = $('#filter');
	var statusContainer =  $('#filter-status');
	var table = $('#results-table');
	var alertContainer = $('#result-alert');
	var filterReset = $('#filter-reset');
	var addFilterElem = $('.add-filter');
    var paginator = $('#paginator');
    var paginationInfo = $( '#paginator-info' );

    var csrfToken = '{{ csrf_token() }}';
	var filterUrl = '{{ route('bank.filter') }}';
	var resetFilterUrl = '{{ route('bank.resetFilter') }}';
	var createNewRecordUrl = '{{ route('people.create') }}';
	var giveBouqiqueCouponUrl = '{{ route('bank.giveBoutiqueCoupon') }}';
	var giveDiapersCouponUrl = '{{ route('bank.giveDiapersCoupon') }}';
	var storeTransactionUrl = '{{ route('bank.storeTransaction') }}';

	var transactionMaxAmount = {{ $single_transaction_max_amount }};
@endsection

@section('footer')
	<script src="{{asset('js/bank.js')}}?v={{ $app_version }}"></script>
@endsection
