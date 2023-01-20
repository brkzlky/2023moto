@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <img src="{{ asset('site/assets/images/logo-dark.svg') }}" />
    @endcomponent
@endslot

<h3>Hey {{ $name }}</h3>
<p>Your password successfully changed. From now on you can login with your new password.</p>

@component('mail::button', ['url' => $url])
Login
@endcomponent

Thanks,<br>
motovago team
@endcomponent
