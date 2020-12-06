@props([
    'label' => 'Delete',
    'icon' => 'trash',
    'confirmation' => 'Do you really want to delete this item?'
])
<button
    type="submit"
    data-confirmation="{{ $confirmation }}"
    {{ $attributes->merge(['class' => 'btn btn-danger delete-confirmation' ] )}}
>
    @isset($icon) <x-icon :icon="$icon"/> @endisset
    @isset($label) {{ $label }} @endisset
</button>
