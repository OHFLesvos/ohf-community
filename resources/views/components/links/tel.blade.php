{{-- Usage:
    <x-links.tel>+1 234 56 78 90</x-links.tel>
--}}
<a href="tel:{{ preg_replace('/[^+0-9]/', '', $slot) }}">{{ $slot }}</a>
