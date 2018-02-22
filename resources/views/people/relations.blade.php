@extends('layouts.app')

@section('title', 'Edit Relations of ' . $person->family_name . ' '. $person->name)

@section('content')

	{{--  <p>Relations of @include('people.person-label'):</p>  --}}

	@if(isset($person->mother) || isset($person->father) || isset($person->partner) || count($person->children) > 0)
		<table class="table table-sm">
			<thead>
				<tr>
					<th>Relation</th>
					<th>Person</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@isset($person->mother)
					<tr>
						<td>Mother</th>
						<td>
							<a href="{{ route('people.relations', $person->mother) }}">
								@include('people.person-label', ['person'=> $person->mother])
							</a>
						</td>
						<td class="text-right">
							<form action="{{ route('people.removeMother', $person) }}" method="post" class="d-inline">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}
								<button type="submit" class="btn btn-link btn-sm" title="Remove relation">@icon(user-times)</button>
							</form>
						</td>
					</tr>
				@endisset
				@isset($person->father)
					<tr>
						<td>Father</th>
						<td>
							<a href="{{ route('people.relations', $person->father) }}">
								@include('people.person-label', ['person'=> $person->father])
							</a>
						</td>
						<td class="text-right">
							<form action="{{ route('people.removeFather', $person) }}" method="post" class="d-inline">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}
								<button type="submit" class="btn btn-link btn-sm" title="Remove relation">@icon(user-times)</button>
							</form>
						</td>
					</tr>
				@endisset
				@isset($person->partner)
					<tr>
						<td>Partner</td>
						<td>
							<a href="{{ route('people.relations', $person->partner) }}">
								@include('people.person-label', ['person'=> $person->partner])
							</a>
						</td>
						<td class="text-right">
							<form action="{{ route('people.removePartner', $person) }}" method="post" class="d-inline">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}
								<button type="submit" class="btn btn-link btn-sm" title="Remove relation">@icon(user-times)</button>
							</form>
						</td>
					</tr>
				@endisset
				@if (count($person->children) > 0)
					@foreach($person->children->sortByDesc('age') as $child) 
						<tr>
							<td>Child</td>
							<td>
								<a href="{{ route('people.relations', $child) }}">
									@include('people.person-label', ['person' => $child])
								</a>
							</td>
							<td class="text-right">
								<form action="{{ route('people.removeChild', [$person, $child]) }}" method="post" class="d-inline">
									{{ csrf_field() }}
    								{{ method_field('DELETE') }}
									<button type="submit" class="btn btn-link btn-sm" title="Remove relation">@icon(user-times)</button>
								</form>
							</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	@endif

	@if (count($types) > 0)
		<div class="card mb-3">
			<div class="card-header">Add a new relation</div>
			<div class="card-body pb-sm-1">
				{!! Form::model($person, ['route' => ['people.addRelation', $person], 'method' => 'post']) !!}
					<div class="form-row">
						<div class="col-sm-auto mb-3">
							{{ Form::bsRadioList('type', $types, 'child') }}
						</div>
						<div class="col-sm">
							{{ Form::bsText('relativeSearch', null, ['placeholder' => 'Search relative', 'rel' => 'autocomplete', 'data-autocomplete-url' => route('people.filterPersons'), 'data-autocomplete-update' => '#relative'], '') }}
							{{ Form::hidden('relative', null, [ 'id' => 'relative' ]) }}
						</div>
						<div class="col-sm-auto">
							{{ Form::bsSubmitButton('Add', 'user-plus') }}
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	@endif

	@empty($person->gender)
		@component('components.alert.info')
			No gender specified, cannot add children. <a href="{{ route('people.edit', $person) }}">@icon(pencil) Edit person</a>
		@endcomponent
	@endempty

@endsection
