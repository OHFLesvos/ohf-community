@component('mail::message')
# @lang('Registration confirmation')

@lang('Dear :name.', [ 'name' => $user->name ])

@lang('Thanks for registering an account at :app_name with your e-mail address :email.', [ 'app_name' => config('app.name'), 'email' => $user->email ])

@component('mail::button', ['url' => route('userprofile')])
@lang('View your profile')
@endcomponent

@lang('Thanks, <br>
:name', [ 'name' => config('app.name') ])
@endcomponent
