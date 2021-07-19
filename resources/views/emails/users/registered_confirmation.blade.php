@component('mail::message')
# {{ __('Registration confirmation') }}

{{ __('Dear :name.', [ 'name' => $user->name ]) }}

{{ __('Thanks for registering an account at :app_name with your e-mail address :email.', [ 'app_name' => config('app.name'), 'email' => $user->email ]) }}

@component('mail::button', ['url' => route('userprofile')])
{{ __('View your profile') }}
@endcomponent

{{ __('Thanks, <br>
:name', [ 'name' => config('app.name') ]) }}
@endcomponent
