@component('components.form.bsInput', [ 'name' => form_id_string($name), 'label' => $label, 'help' => $help, 'attributes' => $attributes ])
    @php
        if (isset($attributes['list']) && is_array($attributes['list'])) {
            $list_items = $attributes['list'];
            $attributes['list'] = form_id_string($name) . '-list';
        }
    @endphp
    {{ Form::text($name, $value, array_merge([ 'id' => form_id_string($name), 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : '') ], $attributes)) }}
    @isset($list_items)
        <datalist id="{{ form_id_string($name) }}-list">
            @foreach($list_items as $item)
                <option value="{{ $item }}">
            @endforeach
        </datalist>
    @endisset
@endcomponent
