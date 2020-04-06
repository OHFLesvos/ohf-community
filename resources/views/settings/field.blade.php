@if($field['type'] == 'number')
    {{ Form::bsNumber($field_key, $field['value'], $field['args'] ?? [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
@elseif($field['type'] == 'select')
    {{ Form::bsSelect($field_key, $field['list'], $field['value'], [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
@elseif($field['type'] == 'checkbox')
    <div class="mb-3">{{ Form::bsCheckbox($field_key, 1, $field['value'], $field['label'], $field['help'] ?? null) }}</div>
@elseif($field['type'] == 'textarea')
    {{ Form::bsTextarea($field_key, $field['value'], $field['args'] ?? [ 'placeholder' => $field['placeholder'] ?? null, 'rows' => 5 ], $field['label'], $field['help'] ?? null) }}
@else
    {{ Form::bsText($field_key, $field['value'], $field['args'] ?? [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
@endif
