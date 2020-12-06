@props([
    'label',
    'icon' => 'check'
])
<button
    type="submit"
    {{ $attributes->merge(['class' => 'btn btn-primary' ] )}}
>
    @isset($icon) <x-icon :icon="$icon"/> @endisset
    {{ $label }}
</button>
