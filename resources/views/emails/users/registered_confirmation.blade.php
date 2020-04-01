@component('mail::message')
# @lang('userprofile.registration_confirmation')

@lang('userprofile.dear_name', [ 'name' => $user->name ])

@lang('userprofile.thanks_for_registering_account_with_mail', [ 'app_name' => config('app.name'), 'email' => $user->email ])

@component('mail::button', ['url' => route('userprofile')])
@lang('userprofile.view_your_profile')
@endcomponent

@lang('app.thanks_name', [ 'name' => config('app.name') ])
@endcomponent