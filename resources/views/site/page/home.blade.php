@extends('site.layout.master')

@section('main_content')

    @include('site.module.home.jumbotron')

    @include('site.module.home.categories')

    @include('site.module.home.car_slider')

@endsection

@section('js')
<script src="{{ secure_asset('site/vue/homepage/jumbotron.js') }}"></script>
@endsection