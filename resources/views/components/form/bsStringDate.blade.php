@component('components.form.bsInput', [ 'name' => $name, 'label' => $label, 'help' => $help ])
    {{ Form::text($name, $value, array_merge([ 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}', 'title' => 'YYYY-MM-DD', 'placeholder' => 'YYYY-MM-DD' ], $attributes)) }}
@endcomponent
