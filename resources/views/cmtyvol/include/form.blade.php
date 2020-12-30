@foreach($fields as $field)
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
        <div class="mb-3 column-break-avoid">{{ Form::bsRadioList($field['name'], $field['list'], $field['value'], $field['label'], $field['help'] ?? null) }}</div>
    @elseif($field['type'] == 'checkboxes')
        <div class="mb-3 column-break-avoid">{{ Form::bsCheckboxList($field['name'].'[]', $field['list'], $field['value'], $field['label'], $field['help'] ?? null) }}</div>
    @elseif($field['type'] == 'listwithdaterange')
        <div class="mb-3 column-break-avoid">{{ Form::bsListWithDateRange($field['name'], $field['list'], $field['value'], $field['label'], $field['help'] ?? null) }}</div>
    @elseif($field['type'] == 'select')
        {{ Form::bsSelect($field['name'], $field['list'], $field['value'], [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
    @elseif($field['type'] == 'image')
        {{ Form::bsFile($field['name'], [ 'accept' => 'image/*' ], $field['label'], $field['help'] ?? null) }}
        @isset($field['value'])
            <div class="mb-3">{{ Form::bsCheckbox($field['name'].'_delete', 1, null, __('app.remove_image')) }}</div>
        @endisset
    @else
        @php
            $args = [];
            if (isset($field['autocomplete'])) {
                $args['list'] = $field['autocomplete'];
            }
            if (isset($field['required'])) {
                $args[] = 'required';
            }
            $args['placeholder'] = $field['placeholder'] ?? null
        @endphp
        {{ Form::bsText($field['name'], $field['value'], $args, $field['label'], $field['help'] ?? null) }}
    @endif
@endforeach
