@extends('layouts.app')

@section('title', 'Duplicates')

@section('content')

	@php
		$count = count($duplicates);
	@endphp
	@if ($count > 0)
		<p>Found <strong>{{ $count }}</strong> duplicates out of <strong>{{ $total }}</strong> total:</p>
		{!! Form::open(['route' => 'people.applyDuplicates', 'method' => 'post']) !!}
			@foreach ($duplicates as $name => $persons)
				<div class="card mb-4">
					<div class="card-header">
						{{ $name }}
					</div>
					<div class="card-body">
						@foreach ($persons as $person)
							<p>@include('people.duplicateDetails')</p>
						@endforeach
					</div>
					<div class="card-footer text-right">
						@php
							$action = 'nothing';
						@endphp
						{{ Form::bsRadioInlineList('action[' . collect($persons)->implode('id', ',') . ']', $actions, $action) }}
					</div>
				</div>
			@endforeach
			<p>{{ Form::bsSubmitButton('Apply') }}</p>
		{!! Form::close() !!}
	@endif

	@empty($duplicates)
		@component('components.alert.info')
			No duplicates found.
		@endcomponent
	@endempty

@endsection
