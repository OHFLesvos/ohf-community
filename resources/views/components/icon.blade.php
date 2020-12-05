@props([
    'icon',
    'style' => 'fas'
])
<i {{ $attributes->merge(['class' => $style . ' fa-' . $icon ]) }}></i>
