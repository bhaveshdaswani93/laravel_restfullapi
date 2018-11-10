@component('mail::message')
# Hello {{$user->name}}

Thanks for creating an account with us, kindly verify your email using the below link:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent