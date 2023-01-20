@extends('site.layout.master')

@section('main_content')
<form action="{{ route('member.complete_listing') }}" id="member_create_listing" method="POST" enctype="multipart/form-data">
	@csrf
	<input type="hidden" name="listing_type" :value="listingType">
    <section class="c-section c-section--sm-bottom" :class="firstCompleted ? 'd-none' : null">
        <div class="loadbox" v-if="!showFirstDetail"></div>
        <div class="container" :class="showFirstDetail ? null : 'd-none'">
            <div class="row">
                <!-- page title -->
                <div class="col-12">
                    <div class="c-title">
						<div class="alert alert-danger" v-if="hasError">
							{{ __('alert.complete_required') }}
						</div>
						@if(Session::has('error'))
                        <div class="alert alert-danger">
							{{ Session::get('error') }}
						</div>
                        @endif
                        <h3 class="c-title__heading c-title__heading--regular">
                            {{ __('user/listings.create_listing_title') }}
                        </h3>
                        <p class="c-title__desc fs14">
                            {{ __('user/listings.create_listing_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <ul class="nav row c-air-tab__list">
                <li class="nav-item c-air-tab__item col-10 col-sm-6 col-lg-3" v-for="v in vehicles" @click="selectCategory(v.category_guid,v.slug)">
                    <div class="nav-link c-air-tab__card" :id="'pills-'+v?.slug+'-tab'" data-bs-toggle="pill" :data-bs-target="'#pills-'+v?.slug">
                        <div class="c-air-tab__img">
                            <img :src="'{{ secure_asset('storage/images/categories/') }}/'+v?.image" alt="">
                        </div>
                        <div class="c-air-tab__content">
                            @{{ lang == 'ar' ? v?.name_ar : v?.name_en }}
                        </div>
                    </div>
                </li>
				<input type="hidden" name="category_guid" :value="selectedCategory">
            </ul>
            <div class="tab-content c-air-tab__content">
                <div class="tab-pane c-air-tab__pane fade" :id="'pills-'+v?.slug" role="tabpanel" :aria-labelledby="'pills-'+v?.slug+'-tab'" v-for="v in vehicles">
                    <div class="c-title">
                        <h3 class="c-title__heading c-title__heading--regular">
                            {{ __('user/listings.what_do_you_want') }}
                        </h3>
                        <p class="c-title__desc fs14">
                            {{ __('user/listings.what_do_you_want_desc') }}
                        </p>
                    </div>
                    <ul class="row listing-type__list">
                        <li class="listing-type__item col-12 col-sm-6 col-lg-6" @click="selectListingType('sell')">
                            <div class="listing-type__item-sub" :class="listingType == 'sell' ? 'active' : null">
                                <div class="c-air-tab__content">
                                    {{ __('user/listings.want_to_sell') }}
                                </div>
                            </div>
                        </li>
                        <li class="listing-type__item col-12 col-sm-6 col-lg-6" @click="selectListingType('rent')">
                            <div class="listing-type__item-sub" :class="listingType == 'rent' ? 'active' : null">
                                <div class="c-air-tab__content">
                                    {{ __('user/listings.want_to_rent') }}
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="listing-about op0" v-if="listingType != null">
                        <div class="c-title">
                            <h3 class="c-title__heading c-title__heading--regular">
                                {{ __('user/listings.about_vehicle') }}
                            </h3>
                            <p class="c-title__desc fs14">
                                {{ __('user/listings.about_vehicle_desc') }}
                            </p>
                        </div>
                        <div class="c-air-tab__form">
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-subcat-select c-select2--air" name="subcategory_guid" :value="selectedVehicleType" style="width: 100%;">
                                    <option value=""></option>
                                    <option :value="sc.category_guid" v-for="sc in subcats">@{{ lang == 'ar' ? sc.name_ar : sc.name_en }}</option>
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-location-select c-select2--air" name="location_guid" :value="selectedLocation" style="width: 100%;">
                                    <option value="">@{{ lang == 'ar' ? '' : 'Location' }}</option>
                                    <option :value="l.location_guid" :data-cur="l?.currency?.label" :data-cnt="l.country_guid" :data-cntlabel="l.country.name" :data-lat="l.country.latitude" :data-lng="l.country.longitude" v-for="l in locations">
                                        @{{ lang == 'ar' ? l.name_ar : l.name_en }}
                                    </option>
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-situation-select c-select2--air" name="situation" :value="selectedSituation" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="used">@{{ lang == 'ar' ? 'سيارة مستعملة' : 'Used' }}</option>
                                    <option value="new">@{{ lang == 'ar' ? 'الجديد' : 'New' }}</option>
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-brand-select c-select2--air" name="brand_guid" :value="selectedBrand" style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-model-select c-select2--air" name="model_guid" :value="selectedModel" style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-year-select c-select2--air" name="year" :value="selectedYear" style="width: 100%;">
                                    <option value=""></option>
                                    @for($i = date('Y'); $i >= date('Y', strtotime("-130 years")) ; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-trim-select c-select2--air" name="trim_guid" :value="selectedTrim" style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <input type="number" name="mileage" class="js-adv-mileage c-input c-input--air" :value="selectedMileage" @keyup="setMileage($event)" placeholder="{{ __('module/label.mileage') }}">
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-fuel-select c-select2--air" name="fuel_type" :value="selectedFuel" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="gasoline">@{{ lang == 'ar' ? 'الغازولين' : 'Gasoline' }}</option>
                                    <option value="diesel">@{{ lang == 'ar' ? 'ديزل' : 'Diesel Fuel' }}</option>
                                    <option value="lpg">@{{ lang == 'ar' ? 'غاز البترول المسال' : 'LPG Gas' }}</option>
                                    <option value="electric">@{{ lang == 'ar' ? 'سيارة كهربائية' : 'Electric Car' }}</option>
                                </select>
                            </div>
                            <div class="c-air-tab__form-col">
                                <select class="js-adv-color-select c-select2--air" name="color_guid" :value="selectedColor" style="width: 100%;">
                                    <option value=""></option>
                                    <option :value="c.color_guid" v-for="c in colors">@{{ lang == 'ar' ? c.name_ar : c.name_en }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="c-air-tab__form-footer">
                            <button type="button" class="c-button c-button--black c-button--sm c-button--md-w c-button--uppercase" @click="completeFirstStep(v.slug)">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="c-section c-section--sm-bottom" :class="!firstCompleted ? 'd-none' : null">
        <div class="loadbox" v-if="!showSecondDetail"></div>
		<div class="container" :class="showSecondDetail ? null : 'd-none'">
			<div class="row">
				<!-- page title -->
				<div class="col-12 col-md-10">
					<div class="c-title">
						<div class="alert alert-danger" v-if="hasError">
							{{ __('alert.complete_required') }}
						</div>
						<h3 class="c-title__heading c-title__heading--regular">
							{{ __('user/listings.detail_title') }}
						</h3>
						<div class="c-title__desc">
							{{ __('user/listings.detail_title_desc') }}
						</div>
					</div>
				</div>
				<!-- advert title and desc-->
				<div class="col-12">
					<div class="c-input__row">
						<div class="c-input__label">
							{{ __('module/label.listing_title') }} <span class="c-input__label-req">*</span>
						</div>
						<input type="text" class="c-input c-input--labeled" id="listing_title" name="name_en" @keyup="setTitle($event)" placeholder="(Ex: Clean ford mustang)">
					</div>

					<div class="c-input__row">
						<div class="c-input__label">
							{{ __('module/label.description') }}
						</div>
                        <textarea class="summernote-en" id="listing_desc" name="description" @keyup="setDescription($event)"></textarea>
					</div>
				</div>
				<!-- advert price -->
				<div class="col-12 col-md-5 col-lg-4">
					<div class="c-input__row">
						<div class="c-input__label">
							{{ __('module/label.price') }} <small>(@{{ selectedCurrency }})</small>
						</div>
						<div class="c-input__row">
                            <input type="number" min="0" max="1000000000" step="1" class="form-control c-input" id="listing_price" name="price" @keyup="setPrice($event)" required>
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
										<option :value="selectedCountry">@{{ selectedCountryLabel }}</option>
									</select>
								</div>
								<div class="col-12 col-sm-12 col-lg-12 u-mg-b-10 px-md-1">
									<select class="js-adv-state-select c-select2--air" name="state" style="width: 100%;">
										<option value=""></option>
									</select>
								</div>
								<div class="col-12 col-sm-12 col-lg-12 u-mg-b-10 px-md-1">
									<select class="js-adv-city-select c-select2--air" name="city" style="width: 100%;">
										<option value=""></option>
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
				<div class="col-12 u-mg-t-65 attrbox">
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
                                <input type="checkbox" class="c-checkbox" :value="agif.attribute_guid" :id="agif.slug+''+agif.id" :name="'extras['+ag.ag_guid+'][]'" @click="addAttr(agif.attribute_guid)">
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
						<div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
							<input type="checkbox" class="c-checkbox" id="accepting" name="apply_form" @click="setApply($event)">
							<label class="c-input__label" for="accepting">
								{{ __('module/label.read_and_accept_listing_rules') }}
							</label>
						</div>
						<div class="col-6 col-sm-4 col-md-3">
							<button type="button" @click="completeListing()" class="c-button c-button--black c-button--sm c-button--block c-button--uppercase">
								{{ __('module/button.complete') }}
							</button>
							<button class="d-none" id="submit_create_form" type="submit"></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</form>
@endsection

@section('js')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsFvopLdjzjionS5o3jpTMR4ffodY0a1k&language=tr"></script>
    <script src="{{ secure_asset('site/vue/members/create_listing.js') }}"></script>
@endsection
