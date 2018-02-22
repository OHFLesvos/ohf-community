@extends('layouts.app')

@section('title', 'Create code card')

@section('content')
    @component('components.alert.info')
        Cards should be unique. Only use each document once, and create a new document if you need more cards.
    @endcomponent
    {{ Form::open(['route' => 'bank.createCodeCard']) }}
        {{ Form::bsNumber('pages', 1, ['min' => 1], 'Number of pages') }}
        <p>
            {{ Form::bsSubmitButton('Create PDF', 'file-pdf') }}
        </p>
    {{ Form::close() }}
    <div id="patience-notice" style="display:none">
        @component('components.alert.info')
            Creating the document may take a while, depending on the number of pages. Please be patient.
        @endcomponent
    </div>
@endsection

@section('script')
$(function(){
    $('form').on('submit', function(){
        $('#patience-notice').fadeIn();
    });
});
@endsection
