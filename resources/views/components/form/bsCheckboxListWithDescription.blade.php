@if ($label !== null || ! empty($label))
    <p>{{ $label }}</p>
@endif
@foreach($entries as $entry)
    @if (! in_array("hidden", $entry) || ! $entry["hidden"] || ($value != null && in_array($entry["text"], $value)))
        <div class="custom-control custom-checkbox">
            {{ Form::checkbox($name, $entry["text"], $value != null ? in_array($entry["text"], $value) : null, [ 'class' => 'custom-control-input', 'id' => form_id_string($name, $entry["text"]) ]) }}
            <label class="custom-control-label @if ($entry["hidden"]) text-danger @endif" for="{{ form_id_string($name, $entry["text"]) }}">{{ $entry["text"] }}</label>
            <a tabindex="0" class="description-tooltip fa fa-info-circle" data-toggle="popover" data-trigger="focus" data-content="{{ $entry["description"] }}"></a>
        </div>
    @endif
@endforeach
@if ($errors->has($name))
    <div><small class="text-danger">{{ $errors->first($name) }}</small></div>
@endif
@if (isset($help))
    <small id="{{ $name }}HelpBlock" class="form-text text-muted">
        {{ $help }}
    </small>
@endif
