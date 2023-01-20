@extends('site.layout.master')

@section('main_content')
<div id="listing_filter">
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-between align-items-end">
                <div class="c-title c-title--mb-none">
                    <h6 class="c-title__desc c-title__desc--top c-title__desc--uppercase">
                        {!! __('page/listing.find_vehicle', ['count' => $listings, 'category' => $category->slug]) !!}
                    </h6>
                    <h3 class="c-title__heading c-title__heading--mb-none">
                        {{ __('page/listing.looking_for', ['category' => $category->slug]) }}
                    </h3>
                </div>
                <div class="c-filter__trigger">
                    <button id="filterTrigger" class="c-button c-button--gray c-button--sm c-button--sm-w c-button--icon-left d-md-flex d-lg-none">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#filter') }}"></use>
                        </svg>
                        {{ __('page/listing.filter') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <input type="hidden" id="curcat" value="{{ $category->category_guid }}" v-if="!showFilter">
    <input type="hidden" id="curcatshort" value="{{ $category->slug }}" v-if="!showFilter">
    <input type="hidden" id="curloc" value="{{ Session::get('current_location_guid') }}" v-if="!showFilter">
    <input type="hidden" id="curlocshort" value="{{ Session::get('current_location') }}" v-if="!showFilter">
    <input type="hidden" id="jtsearch" value="{{ json_encode($jtsearch) }}">
    <div class="row" v-if="showFilter">
        <div class="col-0 col-lg-4 col-xl-3">
            @include('site.module.listing.filter')
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
            <div class="row position-relative" v-if="listings.length > 0">
                <div class="col-12 col-sm-12 col-lg-6 col-xl-4" v-for="l in listings">
                    <a :href="detailUrl+'/'+curcatshort+'/'+l?.listing_no" class="c-filter__card">
                        <div class="c-filter__card-head">
                            <img :src="window.location.origin+'/storage/listings/'+l?.listing_no+'/'+l?.main_image.name" v-if="checkImage(l?.listing_no, l?.main_image)">
                            <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1" v-if="!checkImage(l?.listing_no, l?.main_image)">
                        </div>
                        <div class="c-filter__card-body">
                            <div class="c-filter__card-title">
                                @{{ lang == 'ar' ? l?.name_ar : l?.name_en }}
                            </div>
                        </div>
                        <div class="c-filter__card-footer">
                            <div class="c-filter__card-footer-row">
                                <div class="c-filter__card-footer-row-left">
                                    @{{ l?.year }} <span>@{{ l?.mileage }} KM</span>
                                </div>
                                <div class="c-filter__card-footer-row-right c-filter__card-footer-row-right--big">
                                    @{{ l?.currency?.label+' '+l?.price }}
                                </div>
                            </div>
                            <div class="c-filter__card-footer-row">
                                <div class="c-filter__card-footer-row-left">
                                    @{{ formatDate(l?.created_at) }}
                                </div>
                                <div class="c-filter__card-footer-row-right">
                                    @{{ lang == 'ar' ? l?.location?.name_ar : l?.location?.name_en }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12" v-if="totalPage > 1">
                    @include('site.module.global.vuepagination')
                </div>
            </div>
            <div class="row position-relative" v-if="listings.length == 0">
                <div class="col-12">
                    <div class="c-empty c-empty--line">
						<div class="c-empty__left">
							<svg class="c-icon__svg c-icon--lg">
								<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#warn-circle') }}"></use>
							</svg>
						</div>
						<div class="c-empty__body">
							{{ __('alert.no_listing_result') }}
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
    <script src="{{ secure_asset('site/vue/listing_filter.js') }}"></script>
@endsection