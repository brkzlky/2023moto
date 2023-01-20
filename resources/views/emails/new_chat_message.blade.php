@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('site/assets/images/logo-dark.svg') }}" />
        @endcomponent
    @endslot

    <h2 style="color: #222">{{ $name }} send you a message!</h2>
    <h4 style="color: #444"> #{{ $listing_no }} - {{ $listing_title }} </h4>
    <p>{!! $message !!}</p>

    @slot('footer')
        @component('mail::footer', ['url' => config('app.url')])
        Copyright 2022 © All Rights Reserved.
        @endcomponent
    @endslot

@endcomponent
