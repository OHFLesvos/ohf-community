<div class="form-group column-break-avoid">
    @if ($label === null || ! empty($label))
        {{ Form::label($name, $label) }}
        @if(isset($attributes) && in_array('required', $attributes, true))<span class="text-danger" title="{{ __('Required') }}">*</span>@endif
    @endif
    @if (isset($attributes['prepend']) || isset($attributes['append']))
        <div class="input-group">
        @if (isset($attributes['prepend']))
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $attributes['prepend'] }}</span>
            </div>
        @endif
    @endif
    {{ $slot }}
    @if (isset($attributes['prepend']) || isset($attributes['append']))
        @if (isset($attributes['append']))
            <div class="input-group-append">
                {!! $attributes['append'] !!}
            </div>
        @endif
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
    @if (isset($attributes['error']) && ! empty($attributes['error']))
        <small class="form-text text-danger">
            {{ $attributes['error'] }}
        </small>
    @endif
</div>
