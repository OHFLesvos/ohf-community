@if ($label !== null || !empty($label))
    <p>{{ $label }}</p>
@endif
@foreach($entries as $k => $v)
    <div class="custom-control custom-checkbox custom-control-inline">
        {{ Form::checkbox($name, $k, $value != null ? in_array($k, $value) : null, [ 'class' => 'custom-control-input', 'id' => form_id_string($name, $k) ]) }}
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