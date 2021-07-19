@component('mail::message')
# {{ __('User registered') }}

{{ __('The user :name (:email) has created a new account.', [ 'name' => $user->name, 'email' => $user->email ]) }}

@component('mail::button', ['url' => route('users.show', $user)])
{{ __('View User') }}
@endcomponent

{{ __('Thanks, <br>
:name', [ 'name' => config('app.name') ]) }}
@endcomponent
