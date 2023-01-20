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
</head>

<body class="u-pd-0">
	<div class="c-error">
		<div class="c-error__overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="c-error__head">
						<a class="navbar-brand l-navbar__brand" href="index.html">
							<img class="l-navbar__logo" src="{{ secure_asset('site/assets/images/logo.svg') }}" alt="" />
						</a>
					</div>
					<div class="c-error__box">
						<h1 class="c-error__title">{{ __('alert.error_page_title') }}</h1>
						<p class="c-error__content">
							{!! __('alert.error_page_desc') !!}
						</p>
						<a href="{{ route('location.home', ['location' => Session::get('current_location')]) }}" class="c-button c-button--white c-button--sm  c-button--xl-w c-button--uppercase">
                            {{ __('alert.error_page_btn') }}
                        </a>
					</div>
				</div>
			</div>
		</div>
	</div>

    @include('site.module.global.gototop')

    @include('site.module.global.scripts')
</body>

</html>