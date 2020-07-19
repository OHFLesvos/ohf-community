@if ($label !== null || ! empty($label))
    <p>{{ $label }}</p>
@endif
@foreach($entries as $k => $v)
    <div class="custom-control custom-checkbox">
        {{ Form::checkbox($name, $k, $value != null ? in_array($k, $value) : null, [ 'class' => 'custom-control-input', 'id' => form_id_string($name, $k) ]) }}
        <label class="custom-control-label" for="{{ form_id_string($name, $k) }}">{{ $k }}</label>
        <a tabindex="0" class="description-tooltip fa fa-info-circle" data-toggle="popover" data-trigger="focus" data-content="{{ $v }}"></a>
    </div>
@endforeach
@if($value != null)
    @foreach($value as $v)
        @if(! isset($entries[$v]))
            <div class="custom-control custom-checkbox">
                {{ Form::checkbox($name, $v, true, [ 'class' => 'custom-control-input', 'id' => form_id_string($name, $v) ]) }}
                <label class="custom-control-label text-danger" for="{{ form_id_string($name, $v) }}">{{ $v }}</label>
            </div>
        @endif
    @endforeach
@endif
@if ($errors->has($name))
    <div><small class="text-danger">{{ $errors->first($name) }}</small></div>
@endif
@if (isset($help))
    <small id="{{ $name }}HelpBlock" class="form-text text-muted">
        {{ $help }}
    </small>
@endif
