@if ($label !== null || !empty($label))
    <p>{{ $label }}</p>
@endif
@foreach($entries as $k => $v)
@include('components.form.bsRadio', [ 'name' => $name, 'value' => $k, 'checked' => $k == $value, 'label' => $v ])
@endforeach
@if ($errors->has($name))
    <div><small class="text-danger">{{ $errors->first($name) }}</small></div>
@endif
@if (isset($help))
    <small id="{{ $name }}HelpBlock" class="form-text text-muted">
        {{ $help }}
    </small>
@endif