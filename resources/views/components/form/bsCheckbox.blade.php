<div class="custom-control custom-checkbox">
        {{ Form::checkbox($name, $value, $checked, [ 'class' => 'custom-control-input', 'id' => form_id_string($name) ]) }}
        <label class="custom-control-label" for="{{ form_id_string($name) }}">{{ $label }}</label>
</div>