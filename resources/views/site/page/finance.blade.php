@extends('site.layout.master')

@section('main_content')
<div id="finance">
    <input type="hidden" id="lang" value="{{ Session::get('current_language') }}">
    <input type="hidden" id="loanvalue" value="{{ request()->get('loan') }}">
    <section class="c-section u-pd-b-120 u-pd-sm-b-120 u-pd-md-b-80 u-pd-lg-b-50">
        <div class="container">
            <div class="row">
                <!-- page-title -->
                <div class="col-12">
                    <div class="c-title">
                        <h6 class="c-title__desc c-title__desc--top c-title__desc--uppercase">
                            {{ __('page/finance.title') }}
                        </h6>
                        <h3 class="c-title__heading c-title__heading--regular">
                            {{ __('page/finance.subtitle') }}
                        </h3>
                    </div>
                </div>
                <!-- calculator -->
                <div class="col-12">
                    <div class="c-calc">
                        <div class="c-calc__bar">
                            <div class="c-calc__bar-left">
                                <div class="c-calc__bar-item position-relative">
                                    <label class="c-input__label">{{ __('page/finance.loan_amount') }}</label>
                                    <input type="number" class="c-input c-input--air" :class="calcLoanErr ? 'c-calc__bar-err' : null" name="amount" placeholder="100.000 {{ $currency['label'] }}" v-model="loan">
                                </div>
                                <div class="c-calc__bar-item position-relative">
                                    <label class="c-input__label">{{ __('page/finance.maturity') }}</label>
                                    <select class="form-select c-input--select c-input--select-air" :class="calcMaturityErr ? 'c-calc__bar-err' : null" name="maturity" id="maturity" v-model="maturity">
                                        <option value="">{{ __('page/finance.select_maturity') }}</option>
                                        <option :value="rate.period_type+'-'+rate.period" v-for="rate in rates">@{{ rateInfo(rate) }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="c-calc__bar-right">
                                <button class="c-button c-button--black c-button--uppercase c-button--sm c-button--block c-button--regular" v-if="!calcClicked" @click="calcLoan()">
                                    {{ __('page/finance.calculate') }}
                                </button>
                                <button class="c-button c-button--black c-button--uppercase c-button--sm c-button--block c-button--regular" v-if="calcClicked">
                                    <div class="loader-processing"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(Session::has('finance_offer'))
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
    <div class="c-calc__offer-container js-calc-offer-container">
        <section class="c-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="c-title">
                            <h3 class="c-title__heading c-title__heading--regular">{{ __('page/finance.our_offer') }}</h3>
                        </div>
                        <div class="c-calc__offer">
                            <div class="c-calc__offer-item">
                                <p class="c-calc__offer-title">{{ __('page/finance.bank') }}</p>
                                <p class="c-calc__offer-content">@{{ loanresult?.bank }}</p>
                            </div>
                            <div class="c-calc__offer-item">
                                <p class="c-calc__offer-title">{{ __('page/finance.monthly_charge') }}</p>
                                <p class="c-calc__offer-content">@{{ roundPrice(loanresult?.monthly_loan) }} {{ $currency['label'] }}</p>
                            </div>
                            <div class="c-calc__offer-item">
                                <p class="c-calc__offer-title">{{ __('page/finance.principal') }}</p>
                                <p class="c-calc__offer-content">@{{ loanresult?.rate }}</p>
                            </div>
                            <div class="c-calc__offer-item">
                                <p class="c-calc__offer-title">{{ __('page/finance.top_charge') }}</p>
                                <p class="c-calc__offer-content">@{{ roundPrice(loanresult?.total_loan) }} {{ $currency['label'] }}</p>
                            </div>
                        </div>
                        <div class="c-calc__offer-warn">
                            {{ __('page/finance.our_offer_warn') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="{{ route('site.finance_offer_form') }}" class="c-calc__offer-form" method="post">
                            @csrf
                            <!-- text input -->
                            <div class="form-floating c-input--floating__wrapper">
                                <input type="text" class="form-control c-input c-input--floating" id="offerNameSurname" name="fullname" value="" placeholder="{{ __('page/finance.form_name_surname') }}">
                                <label class="c-input__label c-input--floating__label" for="offerNameSurname">
                                    {{ __('page/finance.form_name_surname') }}
                                </label>
                            </div>
                            <!-- identy input -->
                            <div class="form-floating c-input--floating__wrapper">
                                <input type="number" class="form-control c-input c-input--floating" id="offerIdenty" name="identity" value="" placeholder="{{ __('page/finance.form_identity') }}">
                                <label class="c-input__label c-input--floating__label" for="offerIdenty">{{ __('page/finance.form_identity') }}</label>
                            </div>
                            <!-- email input -->
                            <div class="form-floating c-input--floating__wrapper">
                                <input type="email" class="form-control c-input c-input--floating" id="offerEmail" name="email" value="" placeholder="name@example.com">
                                <label class="c-input__label c-input--floating__label" for="offerEmail">{{ __('page/finance.form_email') }}</label>
                            </div>

                            <!-- cell number input -->
                            <div class="form-floating c-input--floating__wrapper">
                                <input type="tel" class="form-control c-input c-input--floating" id="offerPhone" name="phone" value="" placeholder="+1 541341244124">
                                <label class="c-input__label c-input--floating__label" for="offerPhone">{{ __('page/finance.form_cell_number') }}</label>
                            </div>
                            <span class="c-calc__footer">
                                <button id="creditApply" class="c-button c-button--black c-button--uppercase c-button--sm c-button--xl-w c-button--regular">{{ __('page/finance.form_apply_btn') }}</button>
                            </span>
                            <input type="hidden" name="loanresult" :value="JSON.stringify(loanresult)">
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
    <div class="c-filter__card-container js-filter__card-container" v-if="recomends.length > 0">
        <section class="c-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="c-title">
                            <h3 class="c-title__heading c-title__heading--regular">{{ __('page/finance.recomended_cars') }}</h3>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-6 col-xl-4 form-group" v-for="rec in recomends">
                                <a :href="window.location.origin+'/listings/cars/'+rec.listing_no" target="_blank">
                                    <div class="c-filter__card c-filter__card--mb-large">
                                        <div class="c-filter__card-head">
                                            <img :src="recomendpath+'/'+rec.listing_no+'/'+rec.main_image.name" alt="">
                                        </div>
                                        <div class="c-filter__card-body">
                                            <div class="c-filter__card-title">
                                                @{{ lang == 'ar' ? rec.name_ar : rec.name_en }}
                                            </div>
                                        </div>
                                        <div class="c-filter__card-footer">
                                            <div class="c-filter__card-footer-row">
                                                <div class="c-filter__card-footer-row-left">
                                                    @{{ rec.year }} <span>100.000 KM</span>
                                                </div>
                                                <div class="c-filter__card-footer-row-right c-filter__card-footer-row-right--big">
                                                    @{{ rec.currency.label+' '+rec.price }}
                                                </div>
                                            </div>
                                            <div class="c-filter__card-footer-row">
                                                <div class="c-filter__card-footer-row-left">
                                                    @{{ formatDate(rec.created_at) }}
                                                </div>
                                                <div class="c-filter__card-footer-row-right">
                                                    @{{ lang == 'ar' ? rec.location.name_ar : rec.location.name_en }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
</div>
@endsection

@section('js')
<script src="{{ secure_asset('site/vue/finance.js') }}"></script>
@endsection