@component('mail::message')
# @lang('userprofile.user_registered')

@lang('userprofile.the_user_email_has_created_a_new_account', [ 'name' => $user->name, 'email' => $user->email ])

@component('mail::button', ['url' => route('users.show', $user)])
@lang('app.view_user')
@endcomponent

@lang('app.thanks_name', [ 'name' => config('app.name') ])
@endcomponent