<!-- jumbotron -->

@if ($current_location == 'cyprus')
    <div class="c-jumbotron c-jumbotron--has-overlay"
        style="background-image: url({{ asset('site/assets/images/motovago-cyprus-bg.jpeg') }})">
    @else
        <div class="c-jumbotron c-jumbotron--has-overlay">
@endif
<div class="c-jumbotron__content h-100">
    <div class="c-jumbotron__top">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-5">
                    <div class="c-title">
                        <h1
                            class="c-title__heading c-title__heading--white c-title__heading--regular c-title__heading--uppercase">
                            {{ __('page/home.welcome_title') }}
                        </h1>
                        <p class="c-title__desc c-title__desc--white c-title__desc--uppercase">
                            {{ __('page/home.welcome_message', ['location' => $current_location_name]) }}
                        </p>
                    </div>
                </div>
                <div class="col-0 col-lg-3"></div>
                <div class="col-12 col-lg-4">
                    <div class="download-store">
                        <div class="storebtn">
                            <a href="https://apps.apple.com/np/app/motovago/id1644359491" target="_blank">
                                <img src="{{ asset('site/assets/images/applestore.png') }}"
                                    alt="Motovago on Apple Store">
                            </a>
                        </div>
                        <div class="storebtn">
                            <a href="https://play.google.com/store/apps/details?id=com.motovagomobile" target="_blank">
                                <img src="{{ asset('site/assets/images/googleplay.png') }}"
                                    alt="Motovago on Google Play">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="c-jumbotron__bottom">
        <div class="container">
            <div class="row" id="jumbotron">
                <div class="col-xl-10 offset-xl-1 col-lg-12">
                    <form class="c-widget" :action="actionUrl" method="post">
                        <div class="c-widget__form">
                            @csrf
                            <input type="hidden" id="catname"
                                value="{{ count($categories) > 0 ? $categories[0]->slug : 'cars' }}">
                            <input type="hidden" name="category" :value="catname">
                            <input type="hidden" name="location" value="{{ Session::get('current_location') }}">
                            <input type="hidden" name="brand_guid" :value="selectedBrand">
                            <input type="hidden" name="brand" :value="selectedBrandName">
                            <input type="hidden" name="model_guid" :value="selectedModel">
                            <input type="hidden" name="model" :value="selectedModelName">
                            <div class="c-widget__item">
                                <select class="js-widget-type-select c-select2--widget" style="width: 100%;">
                                    <option value="">All</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->category_guid }}">
                                            {{ Session::get('current_language') == 'ar' ? $c->name_ar : $c->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="c-widget__item">
                                <select class="js-widget-brand-select c-select2--widget" style="width: 100%;"></select>
                            </div>
                            <div class="c-widget__item c-widget__item--wide">
                                <select class="js-widget-model-select c-select2--widget" style="width: 100%;"></select>
                            </div>
                            <div class="c-widget__item">
                                <div class="c-widget__dropdown">
                                    <div class="c-widget__dropdown-box dropdown-toggle" id="yearlabel">
                                        {{ __('module/label.year') }}
                                    </div>
                                    <div class="c-widget__dropdown-menu dropdown-menu dropdown-menu-end"
                                        aria-labelledby="search">
                                        <div class="dropdown-menu--profile__body">
                                            <h5 class="c-widget__dropdown-title">Year</h5>
                                            <div class="c-widget__dropdown-data">
                                                <span id="js-year-range__low"></span>
                                                <span class="js-year-range__seperator">-</span>
                                                <span id="js-year-range__high"></span>
                                            </div>
                                            <div id="js-year-range"></div>
                                        </div>
                                        <input type="hidden" name="year_min" id="yearmin_input">
                                        <input type="hidden" name="year_max" id="yearmax_input">
                                        <div class="d-none" id="yearmin_fun" @click="yearMinChange()"></div>
                                        <div class="d-none" id="yearmax_fun" @click="yearMaxChange()"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="c-widget__item c-widget__item--wide">
                                <div class="c-widget__dropdown">
                                    <div class="c-widget__dropdown-box dropdown-toggle" id="kmlabel">
                                        {{ __('module/label.mileage') }}
                                    </div>
                                    <div class="c-widget__dropdown-menu dropdown-menu dropdown-menu-end"
                                        aria-labelledby="search">
                                        <div class="dropdown-menu--profile__body">
                                            <h5 class="c-widget__dropdown-title">{{ __('module/label.mileage') }}</h5>
                                            <div class="c-widget__dropdown-data">
                                                <span id="js-km-range__low"></span>
                                                <span class="js-km-range__seperator">-</span>
                                                <span id="js-km-range__high"></span>
                                            </div>
                                            <div id="js-km-range"></div>
                                        </div>
                                        <input type="hidden" name="km_min" id="kmmin_input">
                                        <input type="hidden" name="km_max" id="kmmax_input">
                                        <div class="d-none" id="kmmin_fun" @click="kmMinChange()"></div>
                                        <div class="d-none" id="kmmax_fun" @click="kmMaxChange()"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button name="jtsearch" class="c-widget__button">
                            {{ __('module/button.search') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
