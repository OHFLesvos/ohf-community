@if ($label === null || ! empty($label))
    <p>{{ $label }}</p>
@endif
<div class="custom-control custom-radio custom-control-inline">
    {{ Form::radio($name, 'm', null, [ 'class' => 'custom-control-input', 'id' => form_id_string($name, 'm') ]) }}
    <label class="custom-control-label" for="{{ form_id_string($name, 'm') }}"><x-icon icon="male"/> {{ __('Male') }}</label>
</div>
<div class="custom-control custom-radio custom-control-inline">
    {{ Form::radio($name, 'f', null, [ 'class' => 'custom-control-input', 'id' => form_id_string($name, 'f') ]) }}
    <label class="custom-control-label" for="{{ form_id_string($name, 'f') }}"><x-icon icon="female"/> {{ __('Female') }}</label>
</div>
@if ($errors->has($name))
    <div><small class="text-danger">{{ $errors->first($name) }}</small></div>
@endif
