
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="google" content="notranslate" />
    <title>Motovago</title>
    <meta name="description" content="Motovago" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('site.module.global.links')
    <!-- Google tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-245100940-1">
    </script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-245100940-1');
    </script>
</head>

<body>

    @include('site.module.global.header')

    @include('site.module.global.mobile_menu')

    @yield('main_content')

    @if(!Session::has('mtvg_cookie'))
    <div class="c-cookies" id="cookiebox">
		<div class="c-cookies__content">
			<div class="c-cookies__icon">
				<svg class="c-icon__svg c-icon--md">
					<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#warn-circle') }}"></use>
				</svg>
			</div>
			<div class="c-cookies__text">
				{{ __('page/home.cookie') }}
			</div>

		</div>
		<a href="#" class="c-button c-button--white c-button--sm  c-button--xl-w c-button--uppercase cookie-accept">ACCEPT</a>
	</div>
    @endif

    @include('site.module.global.gototop')


    @include('site.module.global.scripts')
</body>

</html>
