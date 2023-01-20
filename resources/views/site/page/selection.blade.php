@extends('site.layout.master')

@section('main_content')
    <!-- timezone section -->
    <section class="c-section c-section--md-top">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="c-title">
                        <h3 class="c-title__heading c-title__heading--regular">{{ __('page/selection.make_selection_title') }}</h3>
                        <h5 class="c-title__desc">{{ __('page/selection.make_selection_desc') }}</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($locations as $l)
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="c-tz-card">
                        <a href="{{ route('location.home',['location'=>$l->subdomain]) }}">
                            <div class="c-tz-card__img">
                                <img src="{{ secure_asset('storage/images/locations/pc_photo/'.$l->pc_photo) }}" alt="{{ $l->name_en }}">
                            </div>

                            <div class="c-tz-card__body">
                                <h6 class="c-tz-card__title">{{ $l->name_en }}</h6>
                            </div>

                            <div class="c-tz-card__desc">
                                <p><span>{{ $l->categories_count }}</span> {{ __('general.categories') }}</p>
                                <p><span>{{ $l->listings_count }}</span> {{ __('general.listings') }}</p>
                            </div>

                            <div class="c-tz-card__footer">
                                <svg class="c-icon__svg c-icon--sm">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#arrow-right') }}"></use>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    <section>
        <div class="container mb-5">
            <div class="row">
                <div class="col-12 col-lg-4 offset-lg-4">
                    <div class="download-store">
                        <div class="storebtn">
                            <a href="https://apps.apple.com/np/app/motovago/id1644359491" target="_blank">
                                <img src="{{ asset('site/assets/images/applestore.png') }}" alt="Motovago on Apple Store">
                            </a>
                        </div>
                        <div class="storebtn">
                            <a href="https://play.google.com/store/apps/details?id=com.motovagomobile" target="_blank">
                                <img src="{{ asset('site/assets/images/googleplay.png') }}" alt="Motovago on Google Play">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- adv section -->
    <section class="c-section c-section--no-padding-top">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if(is_null($page_advertise))
                    <div class="c-adv">
                        <img class="c-adv__img" src="{{ secure_asset('site/assets/images/cta.jpg') }}" alt="">
                        <div class="c-adv__left">
                            <div class="c-title c-title--white c-title--mb-none">
                                <h3 class="c-title__heading c-title__heading--regular c-title__heading--white">
                                    {{ __('page/selection.cta_title') }}</h3>
                                <h5 class="c-title__desc c-title__desc--gray c-title__desc--font-light">
                                    {{ __('page/selection.cta_desc') }}</h5>
                            </div>
                        </div>
                    </div>
                    @else
                        @if(!is_null($page_advertise->link))
                        <a href="{{ $page_advertise->link }}" target="_blank">
                            <div class="c-adv">
                                <img class="c-adv__img" src="{{ secure_asset('storage/adv/'.$page_advertise->image) }}" alt="">
                            </div>
                        </a>
                        @else
                        <div class="c-adv">
                            <img class="c-adv__img" src="{{ secure_asset('storage/adv/'.$page_advertise->image) }}" alt="">
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
