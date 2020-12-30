@if ($label !== null || ! empty($label))
    <p class="mb-1">
        {{ $label }}
        @if(isset($attributes) && in_array('required', $attributes, true))<span class="text-danger" title="@lang('app.required')">*</span>@endif
    </p>
@endif
@foreach($entries as $k => $v)
    <div class="custom-control custom-radio">
        {{ Form::radio($name, $k, $k == $value, [ 'class' => 'custom-control-input', 'id' => form_id_string($name, $k) ]) }}
        <label class="custom-control-label" for="{{ form_id_string($name, $k) }}">{{ $v }}</label>
    </div>
@endforeach
@if ($errors->has($name))
    <div><small class="text-danger">{{ $errors->first($name) }}</small></div>
@endif
@if (isset($help))
    <small id="{{ $name }}HelpBlock" class="form-text text-muted">
        {{ $help }}
    </small>
@endif
