@if($field['type'] == 'number')
    {{ Form::bsNumber($field_key, $field['value'], $field['args'] ?? [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
@elseif($field['type'] == 'select')
    {{ Form::bsSelect($field_key, $field['list'], $field['value'], [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
@elseif($field['type'] == 'checkbox')
    <div class="mb-3">{{ Form::bsCheckbox($field_key, 1, $field['value'], $field['label'], $field['help'] ?? null) }}</div>
@elseif($field['type'] == 'textarea')
    {{ Form::bsTextarea($field_key, $field['value'], $field['args'] ?? [ 'placeholder' => $field['placeholder'] ?? null, 'rows' => 5 ], $field['label'], $field['help'] ?? null) }}
@elseif($field['type'] == 'checkbox')
    <div class="mb-3">
        {{ Form::bsCheckbox($field['name'], __('app.yes'), $field['value'] == __('app.yes'), $field['label'], $field['help'] ?? null) }}
    </div>
@elseif($field['type'] == 'radio')
    <div class="mb-3 column-break-avoid">
        {{ Form::bsRadioList($field['name'], $field['list'], $field['value'], $field['label'], $field['help'] ?? null) }}
    </div>
@elseif($field['type'] == 'file')
    <div class="mb-2">
        <label for="{{ $field_key }}">{{ $field['label'] }}</label>
        {{ Form::bsFile($field_key, $field['args'] ?? [], $field['placeholder'] ?? __('app.choose_file'), $field['help'] ?? null) }}
        @isset($field['value'])
            <a href="{{ Storage::url($field['value']) }}" target="_blank">
                @if(Str::startsWith(mime_content_type(Storage::path($field['value'])), 'image/'))
                    <img src="{{ Storage::url($field['value']) }}" class="img-fluid img-thumbnail mb-2" style="max-height: 200px" data-lity>
                @else
                    <p>@lang('app.view_file')</p>
                @endif
            </a>
            <div class="mb-3">{{ Form::bsCheckbox($field_key . '_delete', 1, null, __('app.remove_file')) }}</div>
        @endisset
    </div>
@else
    {{ Form::bsText($field_key, $field['value'], $field['args'] ?? [ 'placeholder' => $field['placeholder'] ?? null ], $field['label'], $field['help'] ?? null) }}
@endif
