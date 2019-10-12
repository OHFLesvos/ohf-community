@extends('layouts.app')

@section('title', __('people::people.register_helper'))

@section('content')
    {!! Form::open(['route' => ['people.helpers.storeFrom']]) !!}
        {{ Form::bsAutocomplete('person_id', null, route('api.people.filterPersons'), ['placeholder' => __('people::people.search_existing_person')], '') }}
		<p>
            {{ Form::bsSubmitButton(__('people::people.use_existing_person')) }} 
			<a href="{{ route('people.helpers.create') }}" class="btn btn-primary">@icon(plus-circle) @lang('people::people.register_new_person')</a>
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
