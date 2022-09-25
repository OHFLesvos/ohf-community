{{-- Font Awesome 6 Icons https://fontawesome.com/v6/icons --}}
@props([
    'icon',
    'style' => 'solid',
    'spin' => false,
    'pulse' => false,
    'fixedWidth' => false
])
@php
    $classes = [
        match($style) {
            'solid' => 'fa-solid',
            'regular' => 'fa-regular',
            'light' => 'fa-light',
            'duotone' => 'fa-duotone',
            'brands' => 'fa-brands',
            default => 'fa'
        },
        'fa-' . $icon,
        $spin ? 'fa-spin' : null,
        $pulse ? 'fa-pulse' : null,
        $fixedWidth ? 'fa-fw' : null,
    ];
@endphp
<i {{ $attributes->merge(['class' => implode(' ', array_filter($classes))]) }}></i>
