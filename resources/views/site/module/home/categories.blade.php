@if(count($all_categories) > 0)
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-10">
                <div class="c-title">
                    <h4 class="c-title__heading">{{ __('page/home.categories_title') }}</h4>
                </div>
            </div>
            <div class="col-2 owl-nav asd">
                <div class="c-slider__arrows">
                    <div class="c-slider__arrows-prev c-slider--featured__prevBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#arrow-left') }}"></use>
                        </svg>
                    </div>
                    <div class="c-slider__arrows-next c-slider--featured__nextBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#arrow-right') }}"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(count($all_categories) <= 4)
    <!-- if f.categories length =< 4 -->
    <div class="container">
        <div class="row">
            @foreach($all_categories as $c)
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <a class="c-featured-card" href="{{ route('site.listing_category',['category' => $c->slug, 'location' => Session::get('current_location')]) }}">
                    <img class="c-featured-card__img" src="{{ secure_asset('storage/images/categories/'.$c->image) }}" alt="{{ $c->name_en }}">
                    <div class="c-featured-card__body">
                        <div class="c-featured-card__content">
                            <h4 class="c-featured-card__title">{{ App::getLocale() == 'en' ? $c->name_en : $c->name_ar }}</h4>
                            <p class="c-featured-card__info"> {{ __('page/home.listings',['count'=>$c->listing_count]) }}</p>
                        </div>
                        <div class="c-featured-card__icon">
                            <svg class="c-icon__svg c-icon--sm">
                                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#arrow-right') }}"></use>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- if f.categories length > 4 -->
    <div class="l-container--left">
        <div class="owl-carousel owl-theme c-slider c-slider--overflowed c-slider--featured">
            @foreach($all_categories as $c)
            <a class="c-featured-card" href="#">
                <img class="c-featured-card__img" src="{{ secure_asset('storage/images/categories/'.$c->image) }}" alt="{{ $c->name_en }}">
                <div class="c-featured-card__body">
                    <div class="c-featured-card__content">
                        <h4 class="c-featured-card__title">{{ App::getLocale() == 'en' ? $c->name_en : $c->name_ar }}</h4>
                        <p class="c-featured-card__info"> {{ __('page/home.listings',['count'=>$c->listing_count]) }}</p>
                    </div>
                    <div class="c-featured-card__icon">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#arrow-right') }}"></use>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</section>
@endif