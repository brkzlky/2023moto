@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <img src="{{ asset('site/assets/images/logo-dark.svg') }}" />
    @endcomponent
@endslot

<h3>Hey {{ $name }}</h3>
<p>A password recovery attempt requires further verification. Please write the code on this email to the password recovery screen.</p>
<p><strong>If you didn't request this action your password is still working , don't worry!</strong></p>

<h2>#{{ $recovery_code }}</h2>

<p>
    <strong>Code Validity :</strong> {{ date('d.m.Y H:i',strtotime($validity)) }} <br>
    <strong>Recovery Attempt :</strong> {{ $attempt }} <br>
</p>

@component('mail::button', ['url' => $url])
Enter Recovery Code
@endcomponent

Thanks,<br>
motovago team

@slot('footer')
    @component('mail::footer', ['url' => config('app.url')])
    Copyright 2022 © All Rights Reserved.
    @endcomponent
@endslot
@endcomponent
