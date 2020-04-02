@component('components.form.bsInput', [ 'name' => form_id_string($name), 'label' => $label, 'help' => $help, 'attributes' => $attributes ])
    {{ Form::select($name, $entries, $value, array_merge([ 'id' => form_id_string($name), 'class' => 'custom-select'.($errors->has($name) ? ' is-invalid' : '') ], $attributes)) }}
@endcomponent
