@component('components.form.bsInput', [ 'name' => form_id_string($name), 'label' => $label, 'help' => $help, 'attributes' => $attributes ])
    {{ Form::textarea($name, $value, array_merge([ 'id' => form_id_string($name), 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : '') ], $attributes)) }}
@endcomponent
