@component('mail::message')
Hello {{ $user->name }},

@component('mail::button', ['url' => url('reset/'.$user->remember_token)])
Reset Your Password
@endcomponent

In case you have any issue recovering your password, please contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
