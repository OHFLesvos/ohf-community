@props([
    'size' => 200
])
<svg viewBox="0 0 1 1" width="{{ $size }}" height="{{ $size }}" style="max-width: 100%; height: auto;">
    <image xlink:href="{{ $slot }}" width="100%" height="100%" preserveAspectRatio="xMidYMid slice"/>
</svg>
