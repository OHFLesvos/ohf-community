@extends('layouts.app')

@section('title', __('people.edit_helper'))

@section('content')

    {!! Form::model($helper, ['route' => ['people.helpers.update', $helper], 'method' => 'put']) !!}

        <div class="columns-3 mb-4">
            @foreach($data as $section_label => $fields)
                @if(!empty($fields))
                    <div class="card mb-4 column-break-avoid">
                        <div class="card-header">{{ $section_label }}</div>
                        <div class="card-body">
                            @foreach($fields as $field)
                                {{-- TODO 'required' --}}
                                @if($field['type'] == 'number')
                                    {{ Form::bsNumber($field['name'], $field['value'], [ 'placeholder' => $field['placeholder'] ?? null, 'prepend' => $field['prefix'] ?? null ], $field['label'], $field['help'] ?? null) }}
                                @elseif($field['type'] == 'email')
                                    {{ Form::bsEmail($field['name'], $field['value'], [ 'placeholder' => $field['placeholder'] ?? null, 'prepend' => $field['prefix'] ?? null ], $field['label'], $field['help'] ?? null) }}
                                @elseif($field['type'] == 'date')
                                    {{ Form::bsDate($field['name'], $field['value'], [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
                                @elseif($field['type'] == 'textarea')
                                    {{ Form::bsTextarea($field['name'], $field['value'], [ 'rows' => 3, 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
                                @elseif($field['type'] == 'checkbox')
                                    <div class="mb-3">{{ Form::bsCheckbox($field['name'], __('app.yes'), $field['value'] == __('app.yes'), $field['label'], $field['help'] ?? null) }}</div>
                                @elseif($field['type'] == 'radio')
                                    <div class="mb-3">{{ Form::bsRadioList($field['name'], $field['list'], $field['value'], $field['label'], $field['help'] ?? null) }}</div>
                                @elseif($field['type'] == 'select')
                                    {{ Form::bsSelect($field['name'], $field['list'], $field['value'], [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
                                @else
                                    @php
                                        $args = [];
                                        if (isset($field['autocomplete'])) {
                                            $args['autocomplete'] = 'off';
                                            $args['rel'] = 'autocomplete';
                                            $args['data-autocomplete-source'] = json_encode(array_values($field['autocomplete']));
                                        }
                                        $args['placeholder'] = $field['placeholder'] ?? null
                                    @endphp
                                    {{ Form::bsText($field['name'], $field['value'], $args, $field['label'], $field['help'] ?? null) }}
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
		<p>
			{{ Form::bsSubmitButton(__('app.update')) }}
		</p>

    {!! Form::close() !!}    

@endsection
