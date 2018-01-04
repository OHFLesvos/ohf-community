<div class="form-group">
    @if ($label === null || !empty($label))
        {{ Form::label($name, $label) }}
    @endif
    @if (isset($attributes['prepend']))
        <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ $attributes['prepend'] }}</span>
        </div>
    @endif
    {{ $slot }}
    @if (isset($attributes['prepend']))
    </div>
    @endif
    @if ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
    @if (isset($help))
        <small id="{{ $name }}HelpBlock" class="form-text text-muted">
            {{ $help }}
        </small>
    @endif
</div>
