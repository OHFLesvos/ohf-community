@component('mail::message')
# @lang('User registered')

@lang('The user :name (:email) has created a new account.', [ 'name' => $user->name, 'email' => $user->email ])

@component('mail::button', ['url' => route('users.show', $user)])
@lang('View User')
@endcomponent

@lang('Thanks, <br>
:name', [ 'name' => config('app.name') ])
@endcomponent
