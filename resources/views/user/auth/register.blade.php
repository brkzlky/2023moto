
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="google" content="notranslate" />
    <title>Motovago | Register</title>
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
                        @include('user.component.global.show_error')
						<h5 class="c-auth__form-title">
							{{ __('general.register') }}
						</h5>

						<form action="{{ route('member.doRegister') }}" method="POST">
                            @csrf
                            <div class="c-auth__form-row">
                                <input type="text" class="form-control c-input text-transform-unset" name="fullname" placeholder="{{ __('module/label.name_surname') }}" value="{{ old('fullname') }}" required>
                            </div>

                            <div class="c-auth__form-row">
                                <input type="tel" maxlength="18" class="form-control c-input text-transform-unset" name="phone" placeholder="{{ __('module/label.phone') }}" value="{{ old('phone') }}" required>
                            </div>

                            <div class="c-auth__form-row">
                                <input type="email" class="form-control c-input text-transform-unset" name="email" placeholder="{{ __('module/label.email') }}" value="{{ old('email') }}" required>
                            </div>

                            <div class="c-auth__form-row">
                                <input type="password" class="form-control c-input text-transform-unset" name="password" placeholder="{{ __('module/label.password') }}" required>
                            </div>

                            <div class="c-auth__form-row">
                                <input type="password" class="form-control c-input text-transform-unset" name="password_confirmation" placeholder="{{ __('module/label.password_again') }}" required>
                            </div>

                            <div class="c-auth__form-row">
                                <input type="checkbox" class="c-checkbox" id="inputMembership" name="agreement">
                                <label class="c-input__label" for="inputMembership">
                                    {{ __('module/label.user_agreement_text') }}
                                </label>
                            </div>

                            <div class="c-auth__form-row">
                                <input type="checkbox" class="c-checkbox" id="inputCommercial" name="data_protection">
                                <label class="c-input__label" for="inputCommercial">
                                    {{ __('module/label.protection_of_personal_data_text') }}
                                </label>
                            </div>

                            <div class="c-auth__form-row">
                                <button name="signup" class="c-button c-button--black c-button--sm c-button--block c-button--uppercase">
                                    {{ __('module/button.register') }}
                                </button>
                            </div>

                            <div class="c-auth__form-row">
                                <p class="c-auth__form-text">
                                    <a href="{{ route('site.privacy_policy') }}">{{ __('module/label.privacy_policy_text') }}</a>
                                </p>
                                <p class="c-auth__form-text">
                                    <a href="{{ route('site.information_on_protection_of_personal_data') }}">{{ __('module/label.ippd_text') }}</a>
                                </p>
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
							<h1 class="c-auth__bg-bottom-title">{!! __('general.become_a_seller') !!}
							</h1>
							<div class="c-auth__bg-bottom-row">
								<div class="c-auth__bg-bottom-l">
									<p class="c-auth__bg-bottom-desc">
										{!! __('general.join_us_and_reach_millions') !!}
									</p>
								</div>
								<div class="c-auth__bg-bottom-r d-none">
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
