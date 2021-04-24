@component('mail::message')
# @lang('app.registration_confirmation')

@lang('app.dear_name', [ 'name' => $user->name ])

@lang('app.thanks_for_registering_account_with_mail', [ 'app_name' => config('app.name'), 'email' => $user->email ])

@component('mail::button', ['url' => route('userprofile')])
@lang('app.view_your_profile')
@endcomponent

@lang('app.thanks_name', [ 'name' => config('app.name') ])
@endcomponent
