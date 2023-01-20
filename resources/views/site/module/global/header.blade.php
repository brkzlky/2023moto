@php
    $route = Request::segment(1);
@endphp
<nav class="navbar navbar-expand-lg static-top l-navbar">
    <div class="container" id="headerbar">
        <div class="l-navbar__left">
            <a class="navbar-brand l-navbar__brand" href="{{ route('site.home') }}">
                <img class="l-navbar__logo" src="{{ secure_asset('site/assets/images/logo-dark.svg') }}" alt="motovago" />
            </a>
            <select class="js-loc-select c-select2--loc" name="state" style="width: 100%;">
                <option value="" selected disabled>{{ Session::get('current_locale') == 'ar' ? 'موقع' : 'Location' }}</option>
                @foreach($locations as $loc)
                    <option value="{{ route('location.home', ['location'=>$loc->subdomain]) }}" {{ isset($current_location) && $current_location == $loc->subdomain ? 'selected' : null }}>
                        {{ Session::get('current_locale') == 'ar' ? $loc->name_ar : $loc->name_en }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="l-navbar__center">
            <div class="l-navbar__menu l-navbar__menu-links d-none d-lg-flex">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item l-navbar__menu-item {{ is_null($route) || $route == 'listings' ? 'is-active' : null }}">
                        <a class="nav-link" href="{{ route('site.home') }}">{{ __('module/menu.home') }}</a>
                    </li>
                    <li class="nav-item l-navbar__menu-item {{ $route == 'finance' ? 'is-active' : null }}">
                        <a class="nav-link" href="{{ route('site.finance') }}">{{ __('module/menu.financing') }}</a>
                    </li>
                    <li class="nav-item l-navbar__menu-item {{ $route == 'expertise' ? 'is-active' : null }}">
                        <a class="nav-link" href="{{ route('site.expertise') }}">{{ __('module/menu.expertise') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="l-navbar__right">
            @if(!Auth::guard('member')->check() || (Auth::guard('member')->check() && Auth::guard('member')->user()->type->id == 1))
            <div class="l-navbar__right-item d-none d-lg-block">
                <a href="{{ route('member.corporateRegister') }}" class="c-button c-button--sm c-button--transparent">
                    {{ __('module/menu.corporate_register') }}
                </a>
            </div>
            @endif
            <div class="l-navbar__right-item">
                <svg class="c-icon__svg c-icon--md dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#search') }}"></use>
                </svg>
                <div class="dropdown-menu dropdown-menu--search dropdown-menu-end" aria-labelledby="search">
                    <div class="dropdown-menu--search__head">
                        <svg class="c-icon__svg c-icon--md" v-if="!onSearch">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#search') }}"></use>
                        </svg>
                        <div class="searchload" v-if="onSearch"></div>
                        <input type="text" class="c-input" placeholder="{{ __('module/label.search') }}" @keyup="searchListing($event)">
                        <svg class="c-icon__svg c-icon--sm dropdown-menu--search__close">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#close') }}"></use>
                        </svg>
                    </div>
                    <div class="dropdown-menu--search__body" v-if="search.length > 0">
                        <a :href="sr.listing_no !== null ? listingpath+'/'+sr.catslug+'/'+sr.slug : 'javascript:;'" class="dropdown-menu--search-item" v-for="sr in search">
                            <span class="dropdown-menu--search-left">
                                @{{ sr.name }}
                            </span>
                            <span class="dropdown-menu--search-right" v-if="sr.category != null">
                                @{{ sr.category }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="l-navbar__right-item">
                <svg class="c-icon__svg c-icon--md dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#world') }}"></use>
                </svg>
                <div class="dropdown-menu dropdown-menu--lang dropdown-menu-end" aria-labelledby="search">
                    <div class="dropdown-menu--lang__body">
                        <a href="{{ route('site.change_language',['language'=>'en']) }}" class="dropdown-menu--lang-item">
                            English
                        </a>
                        <a href="{{ route('site.change_language',['language'=>'ar']) }}" class="dropdown-menu--lang-item">
                            عربي
                        </a>
                    </div>
                </div>
            </div>
            <div class="l-navbar__right-item d-none d-lg-block">
                <svg class="c-icon__svg c-icon--xl dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#profile') }}"></use>
                </svg>
                @if(Auth::guard('member')->check())
                <!-- logged in user dropdown -->
                <div class="dropdown-menu dropdown-menu--profile dropdown-menu-end" aria-labelledby="search">
                    <div class="dropdown-menu--profile__body">
                        <a href="{{ route('member.messages') }}" class="dropdown-menu--profile-item has-notif">
                            {{ __('general.messages') }}
                        </a>
                        <a href="{{ route('member.listings') }}" class="dropdown-menu--profile-item">
                            {{ __('general.listings') }}
                        </a>
                        <a href="{{ route('member.profile') }}" class="dropdown-menu--profile-item">
                            {{ __('general.profile') }}
                        </a>
                        <a href="{{ route('member.favourites') }}" class="dropdown-menu--profile-item">
                            {{ __('general.favorites') }}
                        </a>
                        <a href="{{ route('member.logout') }}" class="dropdown-menu--profile-item">
                            {{ __('general.logout') }}
                        </a>
                    </div>
                </div>
                @else
                <div class="dropdown-menu dropdown-menu--profile dropdown-menu-end" aria-labelledby="search">
                    <div class="dropdown-menu--profile__body">
                        <a href="{{ route('member.register') }}" class="dropdown-menu--profile-item">
                            {{ __('general.register') }}
                        </a>
                        <a href="{{ route('member.login') }}" class="dropdown-menu--profile-item">
                            {{ __('general.login') }}
                        </a>
                        <hr>
                        <a href="#" class="dropdown-menu--profile-item">
                            {{ __('general.support') }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <div class="l-navbar__menu-right d-block d-lg-none">
                <div class="c-hamburger">
                    <div class="c-hamburger__wrapper">
                        <div class="c-hamburger__bar c-hamburger__bar--top"></div>
                        <div class="c-hamburger__bar c-hamburger__bar--middle"></div>
                        <div class="c-hamburger__bar c-hamburger__bar--bottom"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
