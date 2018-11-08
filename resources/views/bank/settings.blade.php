@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')

    {!! Form::open(['route' => ['bank.updateSettings']]) !!}

		@foreach($sections as $section_key => $section_label)
			@if($fields->where('section', $section_key)->count() > 0)
				<h2 class="display-4">{{ $section_label }}</h2>
				<div class="mb-4">
					@foreach($fields->where('section', $section_key) as $field_key => $field)
						@isset($field['include_pre']) 
							@if(is_array($field['include_pre']) && count($field['include_pre']) > 0) 
								@include($field['include_pre'][0], count($field['include_pre']) > 1 ? $field['include_pre'][1] : [])
							@else
								@include($field['include_pre'])
							@endif
						@endisset
						@if($field['type'] == 'number')
							{{ Form::bsNumber($field_key, $field['value'], $field['args'] ?? [], $field['label']) }}
						@else
							{{ Form::bsText($field_key, $field['value'], $field['args'] ?? [], $field['label']) }}
						@endif
						@isset($field['include_post']) 
							@if(is_array($field['include_post']) && count($field['include_post']) > 0) 
								@include($field['include_post'][0], count($field['include_post']) > 1 ? $field['include_post'][1] : [])
							@else
								@include($field['include_post'])
							@endif
						@endisset
					@endforeach
				</div>
			@endif
		@endforeach

		<p>
			{{ Form::bsSubmitButton(__('app.update')) }}
		</p>

    {!! Form::close() !!}
    
@endsection
