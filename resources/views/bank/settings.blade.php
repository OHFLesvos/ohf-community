@extends('layouts.app')

@section('title', 'Bank Settings')

@section('content')

    {!! Form::open(['route' => ['bank.updateSettings']]) !!}

        <div class="card mb-4">
            <div class="card-header">Display Settings</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsNumber('people_results_per_page', $people_results_per_page, [ 'min' => 1 ], 'Number of results per page') }}
                    </div>
                </div>
            </div>
        </div>

		<div class="card mb-4">
			<div class="card-header">Transactions</div>
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
						<div class="form-group">
                            {{ Form::bsNumber('transaction_default_value', $transaction_default_value, [ 'min' => 1 ], 'Default value of transaction') }}
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
                            {{ Form::bsNumber('single_transaction_max_amount', $single_transaction_max_amount, [ 'min' => 1 ], 'Maximum amount per single transaction') }}
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-4">
			<div class="card-header">Boutique</div>
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
						<div class="form-group">
                            {{ Form::bsNumber('boutique_threshold_days', $boutique_threshold_days, [ 'min' => 1 ], 'Number of days after which visitor can get new boutique coupon') }}
						</div>
					</div>
				</div>
			</div>
		</div>

		<p>
			{{ Form::bsSubmitButton('Update') }}
		</p>

    {!! Form::close() !!}
    
@endsection
