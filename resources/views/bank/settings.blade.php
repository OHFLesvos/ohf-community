@extends('layouts.app')

@section('title', 'Bank Settings')

@section('buttons')
    <a href="{{ route('bank.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Close</a>
@endsection

@section('content')

    {!! Form::open(['route' => ['bank.updateSettings']]) !!}
		<div class="card">
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
		<br>
		<div class="card">
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
		<br>
        {{ Form::button('<i class="fa fa-save"></i> Update', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }}
    {!! Form::close() !!}
    
@endsection
