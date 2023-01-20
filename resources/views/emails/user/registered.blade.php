@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <img src="{{ asset('site/assets/images/logo-dark.svg') }}" />
    @endcomponent
@endslot

<h3>Welcome to motovago.</h3>
<p>Here are your account details. You'll need them to sign in to motovago.</p>

<p>
    <strong>Name :</strong> {{ $name }} <br>
    <strong>Phone :</strong> {{ $phone }} <br>
    <strong>Email :</strong> {{ $email }} <br>
</p>

Thanks,<br>
motovago team

@slot('footer')
    @component('mail::footer', ['url' => config('app.url')])
    Copyright 2022 © All Rights Reserved.
    @endcomponent
@endslot
@endcomponent
