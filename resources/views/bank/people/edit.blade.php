@extends('layouts.app')

@section('title', __('people.edit_person'))

@section('content')

    <div id="bank-app">
		<edit-person-page
			api-url="{{ route('api.people.update', $person) }}"
			redirect-url="{{ route('bank.people.show', $person) }}"
			:value='@json($person)'
			:countries='@json($countries)'
		></edit-person-page>
	</div>

@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}" defer></script>
@endsection
