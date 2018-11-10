@component('mail::message')
# Hello {{$user->name}}

You have change the email address, kindly verify the same using below link:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent