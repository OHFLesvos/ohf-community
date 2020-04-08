<div class="custom-control custom-checkbox">
        {{ Form::checkbox($name, $value, $checked, [ 'class' => 'custom-control-input'.($errors->has($name) ? ' is-invalid' : ''), 'id' => form_id_string($name) ]) }}
        <label class="custom-control-label" for="{{ form_id_string($name) }}">{{ $label }}</label>
        @if ($errors->has($name))
                <span class="invalid-feedback">{{ $errors->first($name) }}</span>
        @endif
</div>