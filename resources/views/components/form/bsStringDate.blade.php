@component('components.form.bsInput', [ 'name' => form_id_string($name), 'label' => $label, 'help' => $help, 'attributes' => $attributes ])
    {{ Form::text($name, $value, array_merge([ 'id' => form_id_string($name), 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}', 'title' => 'YYYY-MM-DD', 'placeholder' => 'YYYY-MM-DD' ], $attributes)) }}
@endcomponent
