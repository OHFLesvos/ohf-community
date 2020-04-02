@component('components.form.bsInput', [ 'name' => form_id_string($name), 'label' => $label, 'help' => $help, 'attributes' => $attributes ])
    {{ Form::password($name, array_merge([ 'id' => form_id_string($name), 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'autocomplete' => 'new-password' ], $attributes)) }}
@endcomponent
