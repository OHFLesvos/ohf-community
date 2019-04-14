@component('components.form.bsInput', [ 'name' => $name, 'label' => $label, 'help' => $help ])
    {{ Form::text($name, $value, array_merge([ 'class' => 'tags'.($errors->has($name) ? ' is-invalid' : '') ], $attributes)) }}
@endcomponent
