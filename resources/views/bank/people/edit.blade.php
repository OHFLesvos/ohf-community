@extends('layouts.app')

@section('title', __('people.edit_person'))

@section('content')

    <div id="bank-app">
        <edit-person-page
            redirect-url="{{ route('bank.people.show', $person) }}"
            :value='@json($person)'
            :countries='@json($countries)'
        ></edit-person-page>
    </div>

@endsection

@push('footer')
    <script src="{{ mix('js/bank.js') }}" defer></script>
@endpush
