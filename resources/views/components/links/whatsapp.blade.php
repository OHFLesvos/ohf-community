{{-- Usage:
    <x-links.whatsapp>+1 234 56 78 90</x-links.whatsapp>
    <x-links.whatsapp message="Hello world!">+1 234 56 78 90</x-links.whatsapp>
--}}
@props([
    'message' => null
])
{!! whatsapp_link($slot, $message) !!}
