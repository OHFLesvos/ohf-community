<div class="form-group">
    @if ($label === null || !empty($label))
        {{ Form::label($name, $label) }}
    @endif
    {{ $slot }}
    @if ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
    @if (isset($help))
        <small id="{{ $name }}HelpBlock" class="form-text text-muted">
            {{ $help }}
        </small>
    @endif
</div>
