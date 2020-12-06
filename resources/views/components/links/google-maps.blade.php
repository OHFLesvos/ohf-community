{{-- Google Maps link
Usage:
    <x-links.google-maps>51.4934,0.0098</x-links.google-maps>
    <x-links.google-maps query="51.4934,0.0098">Some coordinates</x-links.google-maps>
    <x-links.google-maps query="51.4934,0.0098" place-id="_some_place_id">Some place</x-links.google-maps><br>
 --}}
@props([
    'query' => $slot,
    'placeId' => null,
])
@php
    $url = "https://www.google.com/maps/search/?api=1&query=" . urlencode($query);
    if (isset($placeId)) {
        $url .= "&query_place_id=" . $placeId;
    }
@endphp
<a
  href="{{ $url }}"
  {{ $attributes }}
>{{ filled($slot->toHtml()) ? $slot : $query }}</a>
