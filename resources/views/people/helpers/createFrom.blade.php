@extends('layouts.app')

@section('title', __('people.register_helper'))

@section('content')
    {!! Form::open(['route' => ['people.helpers.storeFrom']]) !!}
        {{ Form::bsText('person_search', null, ['placeholder' => __('people.search_existing_person'), 'rel' => 'autocomplete', 'data-autocomplete-url' => route('people.filterPersons'), 'data-autocomplete-update' => '#person_id'], '') }}
        {{ Form::hidden('person_id', null, [ 'id' => 'person_id' ]) }}
		<p>
            {{ Form::bsSubmitButton(__('people.use_existing_person')) }} 
			<a href="{{ route('people.helpers.create') }}" class="btn btn-primary">@icon(plus-circle) @lang('people.register_new_person')</a>
		</p>
    {!! Form::close() !!}    
@endsection

@section('script')
    function toggleSubmit() {
        if ($('#person_id').val()) {
            $('button[type="submit"]').attr('disabled', false);
        } else {
            $('button[type="submit"]').attr('disabled', true);
        }
    }
    $(function(){
        $('#person_id').on('change', toggleSubmit);
        toggleSubmit();
    });
@endsection
