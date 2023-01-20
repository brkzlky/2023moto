@extends('site.layout.master')

@section('main_content')
<section class="c-section u-pd-b-120 u-pd-sm-b-120 u-pd-md-b-80 u-pd-lg-b-50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                {!! $policy !!}
            </div>
        </div>
    </div>
</section>
@endsection