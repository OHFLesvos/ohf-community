@extends('layouts.app')

@section('title', 'Duplicates')

@section('content')

	@php
		$count = count($duplicates);
	@endphp
	@if ($count > 0)
		<p>Found <strong>{{ $count }}</strong> duplicates out of <strong>{{ $total }}</strong> total:</p>
		{!! Form::open(['route' => 'people.applyDuplicates', 'method' => 'post']) !!}
			@foreach ($duplicates as $dp)
				<div class="card mb-4">
					<div class="card-header">
						{{ $dp['person']->family_name }} {{ $dp['person']->name }}
					</div>
					<div class="card-body">
						@include('people.duplicateDetails', ['person' => $dp['person']])<br>
						@foreach ($dp['duplicates'] as $duplicate)
							@include('people.duplicateDetails', ['person' => $duplicate])<br>
						@endforeach
					</div>
					<div class="card-footer text-right">
						{{ Form::bsRadioInlineList('action[' . $dp['person']->id . '][' . $duplicate->id . ']', $actions, 'nothing') }}
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
