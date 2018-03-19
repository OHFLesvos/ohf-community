@component('mail::message')
# User registered

The user {{ $user->name }} ({{ $user->email }}) has created a new account.

@component('mail::button', ['url' => route('users.show', $user)])
View User
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent