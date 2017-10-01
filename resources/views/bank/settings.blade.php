@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    <span class="pull-right">
        <a href="{{ route('bank.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Overview</a> &nbsp;
    </span>

	<h1 class="display-4">Settings</h1>
	<br>

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
	
    {!! Form::open(['route' => ['bank.updateSettings']]) !!}
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col">
						<div class="form-group">
							{{ Form::label('single_transaction_max_amount', 'Maximum amount per single transaction') }}
							{{ Form::number('single_transaction_max_amount', $single_transaction_max_amount, [ 'class' => 'form-control input-normal', 'min' => 1 ]) }}
						</div>
					</div>
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
		<p>
			{{ Form::submit('Update', [ 'name' => 'update', 'class' => 'btn btn-primary' ]) }} &nbsp;
		</p>
    {!! Form::close() !!}
    
@endsection
