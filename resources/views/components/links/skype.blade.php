{{-- Usage:
    <x-links.skype>username</x-links.skype>
    <x-links.skype action="call">username</x-links.skype>

    Possible actions: chat, call, userinfo, sendfile, add, voicemail
--}}
@props([
    'action' => 'chat',
])
<a href="skype:{{ $slot }}?chat">{{ $slot }}</a>
