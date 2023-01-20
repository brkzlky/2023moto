
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="google" content="notranslate" />
    <title>Motovago</title>
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
							{{ __('general.login_title') }}
						</h5>

						<form action="{{ route('member.doLogin') }}" method="POST">
                            @csrf
							<input type="hidden" name="prevurl" value="{{ $prevurl }}">
                            <div class="c-auth__form-row">
                                <input type="email" class="form-control c-input text-transform-unset" name="email" placeholder="{{ __('module/label.email') }}" value="{{ old('email') }}" required>
                            </div>

                            <div class="c-auth__form-row">
                                <input type="password" class="form-control c-input text-transform-unset" name="password" placeholder="{{ __('module/label.password') }}" required>
                            </div>

                            <div class="c-auth__form-row">
                                <button name="do-login" class="c-button c-button--black c-button--sm c-button--block c-button--uppercase">
                                    {{ __('module/button.login') }}
                                </button>
                            </div>


                            <div class="c-auth__form-row c-auth__form-row--between">
                                <div class="c-auth__form-col">
                                    <input type="checkbox" class="c-checkbox" id="inputChck" name="remember_me">
                                    <label class="c-input__label" for="inputChck">
                                        {{ __('module/label.stay_logged_in') }}
                                    </label>
                                </div>

                                <div class="c-auth__form-col">
                                    <a href="{{ route("member.forgetPassword") }}">
                                        <label class="c-input__label" style="cursor: pointer !important">
                                            {{ __('module/button.forget_password') }}
                                        </label>
                                    </a>
                                </div>
                            </div>
                        </form>

						<div class="c-auth__form-divider"></div>

						<div class="c-auth__form-row d-none">
							<a href="{{ route('member.loginSocial', ['social' => 'instagram']) }}"
								class="c-button c-button--black c-button--sm c-button--block c-button--uppercase c-button--social">
								<img src="{{ secure_asset('site/assets/images/social-logo/insta-logo.svg') }}" alt="">
								{{ __('module/button.login_with_instagram') }}
							</a>
						</div>

						<div class="c-auth__form-row">
							<a href="{{ route('member.loginSocial', ['social' => 'tiktok']) }}"
								class="c-button c-button--black c-button--sm c-button--block c-button--uppercase c-button--social">
								<img src="{{ secure_asset('site/assets/images/social-logo/tiktok-logo.svg') }}" alt="">
								{{ __('module/button.login_with_tiktok') }}
							</a>
						</div>

						<div class="c-auth__form-row">
							<a href="{{ route('member.loginSocial', ['social' => 'google']) }}"
								class="c-button c-button--black c-button--sm c-button--block c-button--uppercase c-button--social">
								<img src="{{ secure_asset('site/assets/images/social-logo/google-logo.svg') }}" alt="">
								{{ __('module/button.login_with_google') }}
							</a>
						</div>

						<div class="c-auth__form-row c-auth__form-row--center-sm">
							<p class="c-auth__form-text">
								{{ __('module/label.privacy_policy_desc_text') }}
								<a href="{{ route('site.privacy_policy') }}">
									{{ __('module/label.privacy_policy_text') }}
								</a>

							</p>
						</div>

						<div class="c-auth__form-row c-auth__form-row--center">
							<p class="c-auth__form-text c-auth__form-text--big">
								{{ __('module/label.dont_have_account') }}
								<a href="{{ route('member.register') }}">
									{{ __('module/button.register') }}
								</a>
							</p>
						</div>

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
								<div class="c-auth__bg-bottom-r">
									<div
										class="c-button c-button--white c-button--sm c-button--xl-w c-button--uppercase">
										{{ __('module/button.start_now') }}
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
