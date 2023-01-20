<!-- mobile menu -->
<div class="c-mobile-menu">
    <div class="c-mobile-menu__header">
        @if (Auth::guard('member')->check())
            <a href="{{ route('member.dashboard') }}" class="c-mobile-menu__header-l">
                <svg class="c-icon__svg c-icon--lg">
                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#profile-outline') }}"></use>
                </svg>
                <span>{{ __('module/menu.my_summary') }}</span>
            </a>
        @else
            <a href="{{ route('member.login') }}" class="c-mobile-menu__header-l">
                <svg class="c-icon__svg c-icon--lg">
                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#profile-outline') }}"></use>
                </svg>
                <span>{{ __('module/menu.signin') }}</span>
            </a>
        @endif
        <div class="c-mobile-menu__header-r">
            <svg class="c-icon__svg c-icon--sm">
                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#close') }}"></use>
            </svg>
        </div>
    </div>
    @if (!Auth::guard('member')->check())
        <ul class="c-mobile-menu__list--main c-mobile-menu__list">
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('site.home') }}">
                    <span>{{ __('module/menu.home') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('site.finance') }}">
                    <span>{{ __('module/menu.financing') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('site.expertise') }}">
                    <span>{{ __('module/menu.expertise') }}</span>
                </a>
            </li>
        </ul>
    @else
        <ul class="c-mobile-menu__list--main c-mobile-menu__list">
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('site.home') }}">
                    <span>{{ __('module/menu.home') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('site.finance') }}">
                    <span>{{ __('module/menu.financing') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('site.expertise') }}">
                    <span>{{ __('module/menu.expertise') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('member.favourites') }}">
                    <span>{{ __('module/menu.favorites') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('member.messages') }}">
                    <span>{{ __('module/menu.messages') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('member.profile') }}">
                    <span>{{ __('module/menu.settings') }}</span>
                </a>
            </li>
            <li class="c-mobile-menu__list-item">
                <a href="{{ route('member.logout') }}">
                    <span>{{ __('module/menu.logout') }}</span>
                </a>
            </li>
        </ul>
    @endif

    <div class="c-mobile-menu__footer">
        <a class="c-button c-button--gray c-button--md" href="">{{ __('module/menu.becomeseller') }}</a>
    </div>
</div>
