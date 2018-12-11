{{ Form::text($name.'_search', null, array_merge([ 
    'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''),
    'rel' => 'autocomplete',
    'data-autocomplete-url' => $autocomplete_url,
    'data-autocomplete-update' => '#'.form_id_string($name)
], $attributes)) }}
{{ Form::hidden($name, $value, [ 'id' => form_id_string($name) ]) }}