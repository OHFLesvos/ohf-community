@component('mail::message')
# Registration confirmation

Dear {{ $user->name }}.

Thanks for registering an account at {{ Config::get('app.name') }} with your e-mail address {{ $user->email }}. 

@component('mail::button', ['url' => route('userprofile')])
View your profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent