@extends('layouts.app')

@section('title', 'Bank Settings')

@section('buttons')
    <a href="{{ route('bank.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Close</a>
@endsection

@section('content')

    {!! Form::open(['route' => ['bank.updateSettings']]) !!}

        <div class="card mb-4">
            <div class="card-header">Display Settings</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('people_results_per_page', 'Number of results per page') }}
                            {{ Form::number('people_results_per_page', $people_results_per_page, [ 'class' => 'form-control input-normal', 'min' => 1 ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<div class="card mb-4">
			<div class="card-header">Transactions</div>
			<div class="card-body">
				<div class="form-row">
					<div class="col">
						<div class="form-group">
							{{ Form::label('transaction_default_value', 'Default value of transaction') }}
							{{ Form::number('transaction_default_value', $transaction_default_value, [ 'class' => 'form-control input-normal', 'min' => 1 ]) }}
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							{{ Form::label('single_transaction_max_amount', 'Maximum amount per single transaction') }}
							{{ Form::number('single_transaction_max_amount', $single_transaction_max_amount, [ 'class' => 'form-control input-normal', 'min' => 1 ]) }}
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-4">
			<div class="card-header">Boutique</div>
			<div class="card-body">
				<div class="form-row">
					<div class="col">
						<div class="form-group">
							{{ Form::label('boutique_threshold_days', 'Number of days after which visitor can get new boutique coupon') }}
							{{ Form::number('boutique_threshold_days', $boutique_threshold_days, [ 'class' => 'form-control', 'min' => 0 ]) }}
						</div>
					</div>
				</div>
			</div>
		</div>

        {{ Form::button('<i class="fa fa-check"></i> Save', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }}
    {!! Form::close() !!}
    
@endsection
