@extends('layouts.app')

@section('title', 'Edit Relations of ' . $person->family_name . ' '. $person->name)

@section('content')

		<p>Relations of @include('people.person-label'):</p>

		<table class="table table-sm">
			<thead>
				<tr>
					<th>Relation</th>
					<th>Person</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(isset($person->mother))
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
				@endif
				@if(isset($person->father))
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
				@endif
				@if(isset($person->partner))
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
				@endif
				@if(count($person->children) > 0)
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


		{!! Form::model($person, ['route' => ['people.update', $person], 'method' => 'put']) !!}
		{{--  <br>
		<div class="form-row">
			<div class="col-sm-auto">
				{{ Form::bsRadioList('relation[]', ['father' => 'Father', 'mother' => 'Mother', 'child' => 'Child', 'partner' => 'Partner'], 'child') }}
			</div>
			<div class="col-sm">
				{{ Form::bsText('relative', null, ['placeholder' => 'Name of relative'], '') }}
			</div>
		</div>  --}}

		{{--  <p>
			{{ Form::bsSubmitButton('Update') }}
		</p>  --}}

    {!! Form::close() !!}

@endsection

@section('script')
	$(function(){

	});
@endsection