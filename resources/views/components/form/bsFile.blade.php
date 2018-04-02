<div class="custom-file mb-3">
    {{ Form::file($name, array_merge([ 'class' => 'custom-file-input'.($errors->has($name) ? ' is-invalid' : ''), 'id' => form_id_string($name) ], $attributes)) }}
    <label class="custom-file-label" for="{{ form_id_string($name) }}">{{ $label }}</label>
    @if ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
    @if (isset($help))
        <small id="{{ $name }}HelpBlock" class="form-text text-muted">
            {{ $help }}
        </small>
    @endif    
</div>
