@props([
    'check',
    'colors' => false,
])
@if($check)
    <x-icon icon="check" {{ $attributes->merge(['class' => $colors ? 'text-success' : null]) }}/>
@else
    <x-icon icon="times" {{ $attributes->merge(['class' => $colors ? 'text-danger' : null]) }}/>
@endif
