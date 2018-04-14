@component('components.form.bsInput', [ 'name' => $name, 'label' => $label, 'help' => $help ])
    @php
        $countries = array_values(Countries::getList(App::getLocale()));
    @endphp
    {{ Form::text($name, $value, array_merge([ 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode($countries) ], $attributes)) }}
@endcomponent
