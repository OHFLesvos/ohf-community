<div class="form-check">
        <label class="form-check-label">
                {{ Form::checkbox($name, $value, $checked, [ 'class' => 'form-check-input' ]) }}
                {{ $label }}
        </label>
</div>