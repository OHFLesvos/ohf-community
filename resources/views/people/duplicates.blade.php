@extends('layouts.app')

@section('title', 'Duplicates')

@section('content')

	@php
		$count = count($duplicates);
	@endphp
	@if ($count > 0)
		<p>Found <strong>{{ $count }}</strong> duplicates out of <strong>{{ $total }}</strong> total:</p>
		{!! Form::open(['route' => 'people.applyDuplicates', 'method' => 'post']) !!}
			<div class="table-responsive">
				<table class="table table-sm">
					<thead>
						<tr>
							<th>Person</th>
							<th>Duplicate</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($duplicates as $dp)
							<tr>
								<td rowspan="{{ count($dp['duplicates']) }}">
									@include('people.duplicateDetails', ['person' => $dp['person']])
								</td>
								@foreach ($dp['duplicates'] as $duplicate)
									@if (!$loop->first)<tr> @endif
										<td>
											@include('people.duplicateDetails', ['person' => $duplicate])
										</td>
										<td>
											{{ Form::bsRadioInlineList('action[' . $dp['person']->id . '][' . $duplicate->id . ']', $actions, 'nothing') }}
										</td>
									</tr>
								@endforeach
						@endforeach
					</tbody>
				</table>
			</div>
			<p>{{ Form::bsSubmitButton('Apply') }}</p>
		{!! Form::close() !!}
	@endif

	@empty($duplicates)
		@component('components.alert.info')
			No duplicates found.
		@endcomponent
	@endempty

@endsection
