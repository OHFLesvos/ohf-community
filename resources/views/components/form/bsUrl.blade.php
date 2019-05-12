@component('components.form.bsInput', [ 'name' => $name, 'label' => $label, 'help' => $help, 'attributes' => $attributes ])
    {{ Form::url($name, $value, array_merge([ 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : '') ], $attributes)) }}
@endcomponent