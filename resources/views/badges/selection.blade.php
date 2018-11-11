@extends('layouts.app')

@section('title', __('people.badges'))

@section('content')
    {!! Form::open(['route' => [ 'badges.make' ], 'method' => 'post', 'files' => true]) !!}
        <p>Select entries (<a href="javascript:;" id="select_all">all</a> / <a href="javascript:;" id="select_none">none</a>):</p>
        <div class="mb-3">
            {{ Form::bsCheckboxList('persons[]', collect($persons)->mapWithKeys(function($e){return [ json_encode($e) => $e['name'] . ' (' . $e['position'] . ')'];}), collect($persons)->map(function($e){return  json_encode($e);})->toArray()) }}
        </div>
        <div class="mb-3 mt-4">
            {{ Form::bsFile('alt_logo', [ 'accept' => 'image/*' ], __('app.choose_alternative_logo'), 'Optional: Upload an alternative logo file.') }}
        </div>
		<p>
			{{ Form::bsSubmitButton(__('app.create')) }}
		</p>
    {!! Form::close() !!}
@endsection

@section('script')
    $(function(){
        $('#select_all').on('click', function(){
            $('input[type="checkbox"]').prop('checked', true);
        });
        $('#select_none').on('click', function(){
            $('input[type="checkbox"]').prop('checked', false);
        });
    });
@endsection