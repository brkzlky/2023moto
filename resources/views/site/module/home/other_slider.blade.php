<!-- showcase slider 3 -->
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-10">
                <div class="c-title">
                    <h4 class="c-title__heading">What Looking For</h4>
                </div>
            </div>
            <div class="col-2">
                <div class="c-slider__arrows">
                    <div class="c-slider__arrows-prev c-slider--showcase-3__prevBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#arrow-left') }}"></use>
                        </svg>
                    </div>
                    <div class="c-slider__arrows-next c-slider--showcase-3__nextBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#arrow-right') }}"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="l-container--left">
        <div class="owl-carousel owl-theme c-slider c-slider--overflowed c-slider--showcase-3">
            <a class="c-showcase-card" href="#">
                <div class="c-showcase-card__body">
                    <img class="c-showcase-card__img" src="{{ secure_asset('site/assets/images/showcase/showcase-1.jpg') }}" alt="">
                    <img class="c-showcase-card__logo" src="{{ secure_asset('site/assets/images/logos/logo-1.svg') }}" alt="">
                </div>
                <div class="c-showcase-card__footer">
                    <div class="c-showcase-card__footer-left">
                        <span class="c-showcase-card__title">{{ __('module/label.for_sale') }}</span>
                        <span class="c-showcase-card__location">Cumeyra, Dubai</span>
                    </div>
                    <div class="c-showcase-card__footer-right">
                        <span class="c-showcase-card__price">$95.234</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
