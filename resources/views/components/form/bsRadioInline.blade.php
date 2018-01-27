<div class="form-check-inline">
        <label class="form-check-label">
                {{ Form::radio($name, $value, $checked, [ 'class' => 'form-check-input' ]) }}
                {{ $label }}
        </label>
</div>