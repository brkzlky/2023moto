@extends('site.layout.master')

@section('main_content')
<section class="c-section u-pd-b-120 u-pd-sm-b-120 u-pd-md-b-80 u-pd-lg-b-50">
    <div class="container">
        <div class="row">
            <!-- page-title -->
            <div class="col-12">
                <div class="c-title">
                    <h6 class="c-title__desc c-title__desc--top c-title__desc--uppercase">
                        {{ __('page/expertise.title') }}
                    </h6>
                    <h3 class="c-title__heading c-title__heading--regular">
                        {{ __('page/expertise.subtitle') }}
                    </h3>
                </div>
            </div>
            <!-- calculator -->
            <div class="col-12">
                <div class="c-calc c-calc--exp">
                </div>
            </div>
        </div>
    </div>
</section>
@if(Session::has('expertise_request'))
<div class="c-feedback__container js-feedback__container d-block">
    <section class="c-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="c-feedback">
                        <div class="c-feedback__head">
                            <svg class="c-icon__svg c-icon--xl">
                                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#success') }}"></use>
                            </svg>
                        </div>
                        <div class="c-feedback__body">
                            <div class="c-feedback__title">
                                <h3 class="c-feedback__title-heading">
                                    {{ Session::get('success_title') }}
                                </h3>
                                <p class="c-feedback__title-desc">
                                    {{ Session::get('success_msg') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endif
<div class="c-calc__exp-container" id="expertise">
    <section class="c-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="c-title">
                        <h3 class="c-title__heading c-title__heading--regular">{{ __('page/expertise.form_title') }}</h3>
                    </div>
                    <form action="{{ route('site.expertise_form') }}" method="post" class="c-calc__exp-form" id="expForm">
                        @csrf
                        <!-- name surname input -->
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="text" class="form-control c-input c-input--floating" name="fullname" id="offerNameSurname" @keyup="setValue($event, 'fullname')" placeholder="{{ __('module/label.name_surname') }}" required>
                            <label class="c-input__label c-input--floating__label" for="offerNameSurname">
                                {{ __('module/label.name_surname') }}
                            </label>
                        </div>
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="text" class="form-control c-input c-input--floating" name="email" id="offerEmail" @keyup="setValue($event, 'email')" placeholder="{{ __('module/label.email') }}" required>
                            <label class="c-input__label c-input--floating__label" for="offerEmail">
                                {{ __('module/label.email') }}
                            </label>
                        </div>
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="text" class="form-control c-input c-input--floating" name="phone" id="offerPhone" @keyup="setValue($event, 'phone')" placeholder="{{ __('module/label.phone') }}" required>
                            <label class="c-input__label c-input--floating__label" for="offerPhone">
                                {{ __('module/label.phone') }}
                            </label>
                        </div>
                        <!-- make input -->
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="text" class="form-control c-input c-input--floating expertise-brands" id="expMake" value="" placeholder="{{ __('module/label.brand') }}" required>
                            <label class="c-input__label c-input--floating__label" for="expMake">
                                {{ __('module/label.brand') }}
                            </label>
                            <input type="hidden" name="brand_guid" id="expMakeVal" class="form-control" :value="selectedBrand"/>
                        </div>
                        <!-- model input -->
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="text" class="form-control c-input c-input--floating" id="expModel" value="" placeholder="{{ __('module/label.model') }}" required>
                            <label class="c-input__label c-input--floating__label" for="expModel">
                                {{ __('module/label.model') }}
                            </label>
                            <input type="hidden" name="model_guid" id="expModelVal" class="form-control" :value="selectedModel" />
                        </div>
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="number" min="1900" max="{{ date('Y', strtotime('+1 years')) }}" step="1" class="form-control c-input c-input--floating" id="expDate" value="" placeholder="0000" name="model_year" @keyup="setValue($event, 'model_year')" required>
                            <label class="c-input__label c-input--floating__label" for="expDate">
                                {{ __('module/label.model_year') }}
                            </label>
                        </div>
                        <!-- model input -->
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="text" class="form-control c-input c-input--floating" id="expVariant" value="" placeholder="{{ __('module/label.trim') }}" required>
                            <label class="c-input__label c-input--floating__label" for="expVariant">
                                {{ __('module/label.trim') }}
                            </label>
                            <input type="hidden" name="trim_guid" id="expVariantVal" class="form-control" :value="selectedTrim"/>
                        </div>
                        <!-- milage input -->
                        <div class="form-floating c-input--floating__wrapper">
                            <input type="number" min="0" max="100000000" step="1" class="form-control c-input c-input--floating" name="mileage" id="expMilage" value="" placeholder="{{ __('module/label.mileage') }}" @keyup="setValue($event, 'mileage')" required>
                            <label class="c-input__label c-input--floating__label" for="expMilage">
                                {{ __('module/label.mileage') }}
                            </label>
                        </div>
                        <span class="c-calc__footer">
                            <button type="button" class="c-button c-button--black c-button--uppercase c-button--sm c-button--xl-w c-button--regular" @click="applyExpertiseForm()">
                                {{ __('module/button.apply') }}
                            </button>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="c-adv__card">
                            <div class="c-adv__card-head">
                                <div class="c-adv__card-icon">
                                    <svg class="c-icon__svg c-icon--sm">
                                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#check') }}"></use>
                                    </svg>
                                </div>
                                <h6 class="c-adv__card-title">{{ __('page/finance.action_title1') }}</h6>
                            </div>
                            <div class="c-adv__card-body">
                                <p class="c-adv__card-content">
                                    {{ __('page/finance.action_desc1') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="c-adv__card">
                            <div class="c-adv__card-head">
                                <div class="c-adv__card-icon">
                                    <svg class="c-icon__svg c-icon--sm">
                                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#check') }}"></use>
                                    </svg>
                                </div>
                                <h6 class="c-adv__card-title">{{ __('page/finance.action_title2') }}</h6>
                            </div>
                            <div class="c-adv__card-body">
                                <p class="c-adv__card-content">
                                    {{ __('page/finance.action_desc2') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="c-adv__card">
                            <div class="c-adv__card-head">
                                <div class="c-adv__card-icon">
                                    <svg class="c-icon__svg c-icon--sm">
                                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#check') }}"></use>
                                    </svg>
                                </div>
                                <h6 class="c-adv__card-title">{{ __('page/finance.action_title3') }}</h6>
                            </div>
                            <div class="c-adv__card-body">
                                <p class="c-adv__card-content">
                                    {{ __('page/finance.action_desc3') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="c-adv c-adv--gradient">
                    <img class="c-adv__img" src="{{ secure_asset('site/assets/images/finance/finance-adv.jpg') }}" alt="">
                    <div class="c-adv__left">
                        <div class="c-title c-title--white c-title--mb-none">
                            <h3 class="c-title__heading c-title__heading--regular c-title__heading--white">
                                {{ __('page/finance.bottom_jumbotron_title') }}
                            </h3>
                            <h5 class="c-title__desc c-title__desc--white c-title__desc--font-light">
                                {{ __('page/finance.bottom_jumbotron_desc') }}
                            </h5>
                        </div>
                    </div>
                    <div class="c-adv__right">
                        <button class="c-button c-button--white c-button--sm c-button--xl-w c-button--uppercase">{{ __('page/finance.bottom_jumbotron_btn') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@if(count($faqs) > 0)
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="c-title">
                    <h6 class="c-title__desc c-title__desc--top c-title__desc--uppercase">
                        {{ __('page/finance.faq_title') }}
                    </h6>
                    <h3 class="c-title__heading c-title__heading--uppercase">
                        {{ __('page/finance.faq_subtitle') }}
                    </h3>
                    <p class="c-title__desc c-title__desc--14 c-title__desc--has-mr-sm">
                        {{ __('page/finance.faq_desc') }}
                    </p>
                </div>
            </div>
            <div class="col-lg-9">
                <!-- accordion component -->
                <div class="c-accordion">
                    @foreach ($faqs as $faq)
                    <div class="c-accordion__row js-accordion-btn {{ $loop->iteration == '1' ? 'is-active' : null }}">
                        <a href="javascript:void(0)" class="{{ $loop->iteration == '1' ? 'is-active' : null }}">
                            <p class="c-accordion__row-title">{{ Session::get('current_locale') == 'ar' ? $faq->question_ar : $faq->question_en }}</p>
                            <div class="c-accordion__icon">
                                <div class="c-accordion__icon-first"></div>
                                <div class="c-accordion__icon-second"></div>
                            </div>
                        </a>
                        <div class="c-accordion__content js-accordion-content" {{ $loop->iteration == '1' ? 'style=display:block;' : 'style=display:none;' }}>
                            <p>{{ Session::get('current_locale') == 'ar' ? $faq->answer_ar : $faq->answer_en }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@section('js')
<script src="{{ secure_asset('site/vue/expertise.js') }}"></script>
@endsection