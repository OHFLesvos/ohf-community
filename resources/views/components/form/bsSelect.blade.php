@component('components.form.bsInput', [ 'name' => $name, 'label' => $label, 'help' => $help ])
    {{ Form::select($name, $entries, $value, array_merge([ 'class' => 'custom-select'.($errors->has($name) ? ' is-invalid' : '') ], $attributes)) }}
@endcomponent
