@extends('site.layout.master')

@section('main_content')
<div id="member_listing_detail">
    <form class="c-section c-section--sm-bottom" action="{{ route('member.edit_listing') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="listing_no" name="listing_no" value="{{ $listing_no }}">
        <div class="loadbox" v-if="!showDetail"></div>
        <div class="container" v-if="showDetail">
            <ul class="nav row c-air-tab__list">
                <li class="nav-item c-air-tab__item col-10 col-sm-6 col-lg-3" v-for="v in vehicles">
                    <div class="nav-link c-air-tab__card" :class="v?.category_guid == listing?.category_guid ? 'active' : 'disabled'" :id="'pills-'+v?.slug+'-tab'" data-bs-toggle="pill" :data-bs-target="'#pills-'+v?.slug">
                        <div class="c-air-tab__img">
                            <img :src="'{{ secure_asset('storage/images/categories/') }}/'+v?.image" alt="">
                        </div>
                        <div class="c-air-tab__content">
                            @{{ lang == 'ar' ? v?.name_ar : v?.name_en }}
                        </div>
                    </div>
                </li>
            </ul>
            <div class="tab-content c-air-tab__content">
                <div class="tab-pane c-air-tab__pane fade" :class="v?.category_guid == listing?.category_guid ? 'show active' : null" :id="'pills-'+v?.slug" role="tabpanel" :aria-labelledby="'pills-'+v?.slug+'-tab'" v-for="v in vehicles">
                    <div class="c-title">
                        @if(Session::has('error'))
                        <div class="alert alert-danger">
							{{ Session::get('error') }}
						</div>
                        @endif
                        <h6 class="c-title__desc c-title__desc--top c-title__desc--uppercase">
                            {{ __('user/listings.detail_title') }}
                        </h6>
                        <h3 class="c-title__heading c-title__heading--regular">
                            @{{ lang == 'ar' ? listing?.name_ar : listing?.name_en }}
                        </h3>
                    </div>
                    <div class="c-air-tab__form">
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-subcat-select c-select2--air" name="subcategory_guid" style="width: 100%;" disabled>
                                <option :value="listing.subcategory?.category_guid" v-if="listing.subcategory != null">@{{ lang == 'ar' ? listing.subcategory?.name_ar : listing.subcategory?.name_en }}</option>
                            </select>
                        </div>
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-location-select c-select2--air" name="location_guid" style="width: 100%;" disabled>
                                <option :value="listing.location_guid">
                                    @{{ lang == 'ar' ? listing.location?.name_ar : listing.location?.name_en }}
                                </option>
                            </select>
                        </div>
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-situation-select c-select2--air" name="situation" style="width: 100%;" disabled>
                                <option value="new" v-if="listing.situation == 'new'">@{{ lang == 'ar' ? 'الجديد' : 'New' }}</option>
                                <option value="used" v-if="listing.situation == 'used'">@{{ lang == 'ar' ? 'سيارة مستعملة' : 'Used' }}</option>
                            </select>
                        </div>
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-brand-select c-select2--air" name="brand_guid" style="width: 100%;" disabled>
                                <option :value="listing.brand.brand_guid" v-if="listing.brand != null">@{{ lang == 'ar' ? listing.brand?.name_ar : listing.brand?.name_en }}</option>
                            </select>
                        </div>
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-model-select c-select2--air" name="model_guid" style="width: 100%;" disabled>
                                <option :value="listing.model.model_guid" v-if="listing.model != null">@{{ lang == 'ar' ? listing.model?.name_ar : listing.model?.name_en }}</option>
                            </select>
                        </div>
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-trim-select c-select2--air" name="trim_guid" style="width: 100%;" disabled>
                                <option :value="listing.trim.trim_guid" v-if="listing.trim != null">@{{ lang == 'ar' ? listing.trim?.name_ar : listing.trim?.name_en }}</option>
                            </select>
                        </div>
                        <div class="c-air-tab__form-col">
                            <input type="number" name="year" class="c-input c-input--air js-adv-year" placeholder="{{ __('module/label.model_year') }}" :value="listing?.year" disabled>
                        </div>
                        <div class="c-air-tab__form-col">
                            <input type="text" name="mileage" class="c-input c-input--air" placeholder="{{ __('module/label.mileage') }}" :value="listing?.mileage">
                        </div>
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-fuel-select c-select2--air" name="fuel_type" style="width: 100%;" disabled>
                                <option value="gasoline" v-if="listing.fuel_type == 'gasoline'">@{{ lang == 'ar' ? 'الغازولين' : 'Gasoline' }}</option>
                                <option value="diesel" v-if="listing.fuel_type == 'diesel'">@{{ lang == 'ar' ? 'ديزل' : 'Diesel Fuel' }}</option>
                                <option value="lpg" v-if="listing.fuel_type == 'lpg'">@{{ lang == 'ar' ? 'غاز البترول المسال' : 'LPG Gas' }}</option>
                                <option value="electric" v-if="listing.fuel_type == 'electric'">@{{ lang == 'ar' ? 'سيارة كهربائية' : 'Electric Car' }}</option>
                            </select>
                        </div>
                        <div class="c-air-tab__form-col">
                            <select class="js-adv-color-select c-select2--air" name="color_guid" :value="selectedColor" style="width: 100%;" disabled>
                                <option :value="listing.color_guid">@{{ lang == 'ar' ? listing.color?.name_ar : listing.color?.name_en }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="c-input__row">
                        <div class="c-input__label">
                            {{ __('module/label.listing_title') }} <span class="c-input__label-req">*</span>
                        </div>
                        <input type="text" class="c-input c-input--labeled" name="name_en" :value="listing?.name_en" placeholder="{{ __('user/listings.listing_title_placeholder') }}" required>
                    </div>

                    <div class="c-input__row">
                        <div class="c-input__label">
                            {{ __('module/label.description') }}
                        </div>
                        <textarea class="summernote-en" name="description" v-html="listing?.description"></textarea>
                    </div>
                </div>
                <!-- advert price -->
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="c-input__row">
                        <div class="c-input__label">
                            {{ __('module/label.price') }}
                        </div>
                        <div class="c-input__row-multiple">
                            <select class="form-select c-input c-input--select" name="currency" id="currency">
                                <option :value="c.currency_guid" :selected="c.currency_guid == listing?.currency_guid" v-for="c in currencies">
                                    @{{ c.label }}
                                </option>
                            </select>
                            <input type="number" class="form-control c-input" name="price" :value="listing?.price">
                        </div>
                    </div>
                </div>
                <!-- divier -->
                <div class="col-12">
                    <hr class="c-divider c-divider--m-md">
                </div>
                <!-- advert image upload -->
                <div class="col-12">
                    <div class="c-input__label">
                        {{ __('module/label.upload_listing_images') }}
                    </div>
                    <div class="listing-img-uploader"></div>
                    <div class="c-input__label">
                        {{ __('module/label.expertise') }}
                    </div>
                    <div class="d-flex align-items-center u-mg-b-50">
                        <button class="c-button c-button--outline-gray-dark c-button--xs c-button--sm-w c-button--icon-left" onclick="document.getElementById('getFile').click()">
                            <svg class="c-icon__svg c-icon--sm">
                                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#pdf') }}"></use>
                            </svg>
                            {{ __('module/label.upload_listing_report') }}
                        </button>
                        <input type="file" id="getFile" name="expertise">
                    </div>
                </div>
                <!-- advert location -->
                <div class="col-12">
                    <div class="c-title c-title--mb-none">
                        <h6 class="c-title__heading c-title__heading--regular c-title__heading--mb-none">
                            {{ __('module/label.location') }}
                        </h6>
                    </div>
                    <hr class="c-divider c-divider--mb-md">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="c-map">
                                <div id="gmap" class="gmap"></div>
                                <input type="hidden" name="latitude" :value="latitude">
                                <input type="hidden" name="longitude" :value="longitude">
                            </div>
                            <div class="dragWarn">{{ __('alert.drag_msg') }}</div>
                        </div>
                        <div class="col-sm-12 col-md-6 u-mg-t-25">
							<div class="row">
								<div class="col-12">
									<label class="c-input__label">{{ __('module/label.address') }}</label>
								</div>
								<div class="col-12 col-sm-12 col-lg-12 u-mg-b-10 px-md-1 d-none">
									<select class="js-adv-country-select c-select2--air" name="country" style="width: 100%;">
										<option :value="listing.country?.country_guid">@{{ listing.country?.name }}</option>
									</select>
								</div>
								<div class="col-12 col-sm-12 col-lg-12 u-mg-b-10 px-md-1">
									<select class="js-adv-state-select c-select2--air" name="state" style="width: 100%;">
										<option :value="listing.state?.state_guid">@{{ listing.state?.name }}</option>
									</select>
								</div>
								<div class="col-12 col-sm-12 col-lg-12 u-mg-b-10 px-md-1">
									<select class="js-adv-city-select c-select2--air" name="city" style="width: 100%;">
										<option :value="listing.city?.city_guid">@{{ listing.city?.name }}</option>
									</select>
								</div>
                                <div class="col-12 px-md-1" v-if="coordError">
									<div class="coorErr">{{ __('alert.coord_error') }}</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
                <!-- advert extras -->
                <div class="col-12 u-mg-t-65">
                    <div class="c-title c-title--mb-none">
                        <h6 class="c-title__heading c-title__heading--regular">
                            {{ __('module/label.extras') }}
                        </h6>
                    </div>
                    <div class="c-checklist" v-for="ag in attributeGroups">
                        <div class="c-title c-title--mb-none">
                            <h6 class="c-title__heading c-title__heading--regular c-title__heading--mb-none">
                                @{{ lang == 'ar' ? ag.name_ar : ag.name_en }}
                            </h6>
                        </div>
                        <div class="c-checklist__body">
                            <div class="c-checklist__item" v-for="agif in ag.attribute_info">
                                <input type="checkbox" class="c-checkbox" :value="agif.attribute_guid" :id="agif.slug+''+agif.id" :name="'extras['+ag.ag_guid+'][]'" :checked="checkAttr(agif.attribute_guid)">
                                <label class="c-checkbox__label" :for="agif.slug+''+agif.id">
                                    @{{ lang == 'ar' ? agif.name_ar : agif.name_en }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- advert desc -->
                <div class="col-12 u-mg-t-60">
                    <div class="c-title">
                        <p class="c-title__desc c-title__desc--14">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut aliquam at lectus eget luctus.
                            Integer ultricies vehicula tincidunt. Fusce convallis eros ipsum. Nunc id tortor laoreet,
                            fringilla eros eu, feugiat ipsum. Duis et diam iaculis, auctor sem sodales, egestas velit.
                            Curabitur sed metus ac orci fermentum tincidunt. Sed sit amet pharetra orci, sed euismod
                            est.

                            Suspendisse pharetra euismod eros et facilisis. Nulla ut fringilla eros, sit amet egestas
                            enim.
                            Maecenas augue nibh, varius venenatis massa vitae, pretium porttitor ante. In consequat
                            molestie
                            ultrices. Integer ac faucibus risus, eu aliquet nisl. Duis pulvinar massa enim, et fringilla
                            eros iaculis id. Morbi pulvinar erat in turpis pretium viverra. Aliquam mi turpis, semper
                            non
                            quam in, aliquet consequat nulla. Mauris nec leo nec tortor congue volutpat. Pellentesque et
                            leo
                            feugiat orci ornare congue. Donec dictum augue in sollicitudin imperdiet. Mauris ultrices
                            ligula
                            ac nisi venenatis ornare. Praesent ultrices mauris facilisis lacus porta, aliquam pulvinar
                            mauris dictum. Aliquam erat volutpat. Vivamus ornare auctor massa, id viverra diam. Morbi
                            luctus
                            erat ut orci sagittis pellentesque.
                        </p>
                    </div>
                    <div class="row d-flex align-items-center justify-content-end">
                        <div class="col-6 col-sm-4 col-md-3">
                            <button type="submit" class="c-button c-button--black c-button--sm c-button--block c-button--uppercase">
                                {{ __('module/button.complete') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsFvopLdjzjionS5o3jpTMR4ffodY0a1k&language=tr"></script>
    <script src="{{ secure_asset('site/vue/members/listing_detail.js') }}"></script>
@endsection

