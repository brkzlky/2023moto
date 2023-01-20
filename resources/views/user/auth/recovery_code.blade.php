
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="google" content="notranslate" />
    <title>Motovago  | Forget Password</title>
    <meta name="description" content="Motovago" />
    @include('site.module.global.links')
</head>

<body>

    @include('site.module.global.header')

    @include('site.module.global.mobile_menu')

    <!-- auth section -->
	<div class="c-auth__wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 pe-lg-0 mb-4 mb-lg-0">
					<div class="c-auth__form">
						<form action="{{ route('member.recoveryCode') }}" method="POST">
                            @csrf
                            <h5 class="c-auth__form-title">
                                {{ __("general.password_recovery_code") }}
                            </h5>
                            <p class="c-auth__form-desc">
                                {{ __("general.password_recovery_code") }}
                            </p>
                            <div class="c-auth__form-row">
                                <input type="email" class="form-control c-input" name="email" placeholder="{{ __('general.email') }}" value="{{ old('email') }}">
                            </div>
                            <div class="c-auth__form-row">
                                <input type="text" class="form-control c-input" name="recovery_code" placeholder="{{ __('general.recovery_code') }}">
                            </div>
                            <div class="c-auth__form-row">
                                <button name="recover" class="c-button c-button--black c-button--sm c-button--block c-button--uppercase">
                                    {{ __("general.continue") }}
                                </button>
                            </div>
                        </form>
					</div>
				</div>
				<div class="col-lg-8 ps-lg-0">
					<div class=" c-auth__bg">
						<div class="c-auth__bg-top">
							<img src="{{ secure_asset('site/assets/images/logo.svg') }}" alt="Motovago">
						</div>
						<div class="c-auth__bg-bottom">
							<h1 class="c-auth__bg-bottom-title">{{ __('global.become_a_seller') }}
							</h1>
							<div class="c-auth__bg-bottom-row">
								<div class="c-auth__bg-bottom-l">
									<p class="c-auth__bg-bottom-desc">
										{{ __('global.join_us_and_reach_millions') }}
									</p>
								</div>
								<div class="c-auth__bg-bottom-r">
									<div
										class="c-button c-button--white c-button--sm c-button--xl-w c-button--uppercase">
										{{ __('global.start_now') }}
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    @include('site.module.global.scripts')
</body>

</html>
