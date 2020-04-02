@component('components.form.bsInput', [ 'name' => form_id_string($name), 'label' => $label, 'help' => $help, 'attributes' => $attributes ])
    {{ Form::text($name, $value, array_merge([ 'id' => form_id_string($name), 'class' => 'tags'.($errors->has($name) ? ' is-invalid' : '') ], $attributes)) }}
@endcomponent
