@extends('site.layout.master')

@section('main_content')
<div id="listing_detail">
    <div class="c-sticky-bar" id="c-sticky-bar">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="c-sticky-bar__inner">
                        <div class="c-sticky-bar__left">
                            <div class="c-breadcrumb c-breadcrumb--has-mb">
                                @php
                                    if(!is_null($listing->brand)) {
                                        if(!is_null($listing->model)) {
                                            if(!is_null($listing->trim)) {
                                                echo $listing->brand->name_en.' > '.$listing->model->name_en.' > '.$listing->trim->name_en;
                                            } else {
                                                echo $listing->brand->name_en.' > '.$listing->model->name_en;
                                            }
                                        } else {
                                            echo $listing->brand->name_en;
                                        }
                                    }
                                @endphp
                            </div>
                            <div class="c-product__title">
                                {{ Session::get('current_language') == 'ar' ? $listing->name_ar : $listing->name_en }}
                            </div>
                            <div class="c-product__address">
                                {{ Session::get('current_language') == 'ar' ? $listing->location->name_ar : $listing->location->name_en }}
                                <span class="c-product__address-cat">{{ Session::get('current_language') == 'ar' ? $category->name_ar : $category->name_en }}</span>
                            </div>
                        </div>
                        <div class="c-sticky-bar__right">
                            <div class="c-product__price">
                                {{ $listing->currency->label }} <span>{{ sprintf('%.0f', $listing->price) }}</span>
                            </div>
                            @if(!Auth::guard('member')->check())
                            <a href="{{ route('member.login') }}" class="c-button c-button--gray c-button--sm c-button--icon-left c-button--sm-w c-button--uppercase c-button--fav">
								<svg class="c-icon__svg c-icon--md">
									<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star') }}">
									</use>
								</svg>
								{{ __('module/label.add_to_fav') }}
                            </a>
                            @else
                            <button type="button" @click="addToFav()" class="c-button c-button--gray c-button--sm c-button--icon-left c-button--sm-w c-button--uppercase c-button--fav" v-if="!isFav">
                                <svg class="c-icon__svg c-icon--md">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star') }}">
                                    </use>
                                </svg>
                                {{ __('module/label.add_to_fav') }}
                            </button>
                            <button type="button" @click="removeFromFav()" class="c-button c-button--gray c-button--sm c-button--icon-left c-button--sm-w c-button--uppercase c-button--fav" v-if="isFav">
                                <svg class="c-icon__svg c-icon--md">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star--filled') }}">
                                    </use>
                                </svg>
                                {{ __('module/label.remove_from_fav') }}
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="c-product--mobile">

        <!-- product gallery mobile -->
        <div class="l-container--left">

            <div class="c-product__slider--mobile ">
                @if(count($listing->images) > 3)
                    @foreach ($listing->images as $li)
                    <a class="c-showcase-card" data-fancybox="gallery" data-src="{{ secure_asset('storage/listings/'.$listing->listing_no.'/'.$li->name) }}">
                        <div class="c-showcase-card__body">
                            <img class="c-showcase-card__img" src="{{ secure_asset('storage/listings/'.$listing->listing_no.'/'.$li->name) }}" alt="">
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>

            <button class="slick-next slick-arrow" aria-label="Next" type="button" style="" aria-disabled="false">Next</button>
        </div>

        <!-- product title mobile -->
        <div class="container">
            <div class="c-product__title c-product__title--mobile">
                {{ Session::get('current_language') == 'ar' ? $listing->name_ar : $listing->name_en }}
            </div>
            <div class="c-product__address">
                {{ Session::get('current_language') == 'ar' ? $listing->location->name_ar : $listing->location->name_en }}
                <span class="c-product__address-cat">{{ Session::get('current_language') == 'ar' ? $category->name_ar : $category->name_en }}</span>
            </div>
        </div>

        <!-- product bottom fixed bar -->
        <div class="c-product__mobile-bar">
            <div class="c-sticky-bar__right">
                <div class="c-product__price">
                    {{ $listing->currency->label }} <span>{{ sprintf('%.0f', $listing->price) }}</span>
                </div>
                @if(!Auth::guard('member')->check())
                <a href="{{ route('member.login') }}" class="c-button c-button--gray c-button--sm c-button--icon-left c-button--sm-w c-button--uppercase c-button--fav">
                    <svg class="c-icon__svg c-icon--md">
                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star') }}">
                        </use>
                    </svg>
                    {{ __('module/label.add_to_fav') }}
                </a>
                @else
                <button type="button" @click="addToFav()" class="c-button c-button--gray c-button--sm c-button--icon-left c-button--sm-w c-button--uppercase c-button--fav" v-if="!isFav">
                    <svg class="c-icon__svg c-icon--md">
                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star') }}">
                        </use>
                    </svg>
                    {{ __('module/label.add_to_fav') }}
                </button>
                <button type="button" @click="removeFromFav()" class="c-button c-button--gray c-button--sm c-button--icon-left c-button--sm-w c-button--uppercase c-button--fav" v-if="isFav">
                    <svg class="c-icon__svg c-icon--md">
                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star--filled') }}">
                        </use>
                    </svg>
                    {{ __('module/label.remove_from_fav') }}
                </button>
                @endif
            </div>
        </div>
    </div>
    <div class="container">
        <div class="c-product__slider">
            @foreach($listing->images as $li)
            <div class="c-product__slider-item">
				<a data-fancybox="gallery" data-src="{{ secure_asset('storage/listings/'.$listing->listing_no.'/'.$li->name) }}">
					<img class="c-product__slider-img" src="{{ secure_asset('storage/listings/'.$listing->listing_no.'/'.$li->name) }}" alt="">
				</a>
			</div>
            @endforeach
        </div>
        <!-- product features -->
        <div class="c-product__features">
            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.listing_no') }}</div>
                <div class="c-product__features-info">#{{ $listing->listing_no }}</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.listing_date') }}</div>
                <div class="c-product__features-info">{{ date('F d, Y', strtotime($listing->created_at)) }}</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.brand') }}</div>
                <div class="c-product__features-info">{{ !is_null($listing->brand) ? Session::get('current_language') == 'ar' ? $listing->brand->name_ar : $listing->brand->name_en : '-' }}</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.model') }}</div>
                <div class="c-product__features-info">{{ !is_null($listing->model) ? Session::get('current_language') == 'ar' ? $listing->model->name_ar : $listing->model->name_en : '-' }}</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.model_year') }}</div>
                <div class="c-product__features-info">{{ $listing->year }}</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.trim') }}</div>
                <div class="c-product__features-info">{{ !is_null($listing->trim) ? Session::get('current_language') == 'ar' ? $listing->trim->name_ar : $listing->trim->name_en : '-' }}</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.mileage') }}</div>
                <div class="c-product__features-info">{{ $listing->mileage }} KM</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.fuel_type') }}</div>
                <div class="c-product__features-info">
                    @php
                        if($listing->fuel_type == 'gasoline') {
                            echo Session::get('current_language') == 'ar' ? 'الغازولين' : 'Gasoline';
                        }
                        if($listing->fuel_type == 'diesel') {
                            echo Session::get('current_language') == 'ar' ? 'ديزل' : 'Diesel Fuel';
                        }
                        if($listing->fuel_type == 'lpg') {
                            echo Session::get('current_language') == 'ar' ? 'غاز البترول المسال' : 'LPG Gas';
                        }
                        if($listing->fuel_type == 'electric') {
                            echo Session::get('current_language') == 'ar' ? 'سيارة كهربائية' : 'Electric Car';
                        }
                    @endphp
                </div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.color') }}</div>
                <div class="c-product__features-info">{{ Session::get('current_language') == 'ar' ? $listing->color->name_ar : $listing->color->name_en }}</div>
            </div>

            <div class="c-product__features-item">
                <div class="c-product__features-title">{{ __('page/listing.listing_status') }}</div>
                <div class="c-product__features-info">
                    @php
                        if($listing->situation == 'new') {
                            echo Session::get('current_language') == 'ar' ? 'الجديد' : 'First Hand';
                        }
                        if($listing->situation == 'used') {
                            echo Session::get('current_language') == 'ar' ? 'سيارة مستعملة' : 'Used Car';
                        }
                    @endphp
                </div>
            </div>
        </div>
        <!-- product provider -->
        @if($listing->user->type->id == '2')
        <div class="c-product__provider c-product__provider--vertical-mobile">
            <a href="{{ route('site.seller_detail',['slug' => $listing->user->slug.'-'.$listing->user->id, 'location' => Session::get('current_location')]) }}" class="c-product__provider-left">
                @if (!is_null($listing->user->logo))
                <img src="{{ secure_asset('storage/user/'.$listing->user->logo) }}" class="c-product__provider-img">
                @else
                <img src="{{ secure_asset('site/assets/images/cover-img.jpg') }}" class="c-product__provider-img">
                @endif
                <div class="c-product__provider-details">
                    <span class="c-product__provider-title">{{ __('page/listing.for_sale_by') }}</span>
                    <span class="c-product__provider-company">{{ $listing->user->name }}</span>
                    @if(!is_null($listing->user->country))
                    <span class="c-product__provider-location">{{ $listing->user->country->name }}</span>
                    @endif
                </div>
            </a>
            <div class="c-product__provider-right">
                @if(Auth::guard('member')->check())
                    @if($listing_user_whatsapp)
                    <a href="https://wa.me/{{ $listing_user_whatsapp }}" class="c-button c-button--gray c-button--only-icon c-button--lg">
                        <svg class="c-icon__svg c-icon--md">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#whatsapp') }}"></use>
                        </svg>
                    </a>
                    @endif
                    @if($listing_user_phone)
                    <a href="tel:{{ $listing_user_phone }}" class="c-button c-button--gray c-button--only-icon c-button--lg">
                        <svg class="c-icon__svg c-icon--md">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#phone') }}"></use>
                        </svg>
                    </a>
                    @endif
                    @if(Auth::guard('member')->user()->user_guid != $listing->user_guid)
                    <div class="c-button c-button--gray c-button--only-icon c-button--lg" @click="openMessageModal()">
                        <svg class="c-icon__svg c-icon--md">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#bubble-double') }}"></use>
                        </svg>
                    </div>
                    @endif
                @else
                <a href="{{ route('member.login') }}" class="c-button c-button--gray c-button--only-icon c-button--lg">
					<svg class="c-icon__svg c-icon--md">
						<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#bubble-double') }}"></use>
					</svg>
				</a>
                @endif
                <div class="c-product__provider-badge">
                    <svg class="c-icon__svg c-icon--sm">
                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#badge-check') }}"></use>
                    </svg>
                    {{ __('page/listing.member_since',['year' => date('Y', strtotime($listing->user->created_at))]) }}
                </div>
            </div>
        </div>
        @endif
        @if($listing->user->type->id == '1')
        <div class="c-product__provider c-product__provider--vertical-mobile">
            <div class="c-product__provider-left">
                <div class="c-product__provider-details">
                    <span class="c-product__provider-title">{{ __('page/listing.for_sale_by') }}</span>
                    <span class="c-product__provider-company">{{ $listing->user->name }}</span>
                    @if(!is_null($listing->user->country))
                    <span class="c-product__provider-location">{{ $listing->user->country->name }}</span>
                    @endif
                </div>
            </div>
            <div class="c-product__provider-right">
                <div class="c-product__provider-badge">
                    <svg class="c-icon__svg c-icon--sm">
                        <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#badge-check') }}"></use>
                    </svg>
                    {{ __('page/listing.member_since',['year' => date('Y', strtotime($listing->user->created_at))]) }}
                </div>
            </div>
        </div>
        @endif
        <!-- product description -->
        <div class="c-product__desc">
            <div class="c-title c-title--mb-normal">
                <h4 class="c-title__heading c-title__heading--regular">
                    {{ __('page/listing.description') }}
                </h4>
            </div>
            <div class="row">
                <div class="col-lg-8 col-xl-9">
                    <div class="c-product__desc-content">
                        {!! $listing->description !!}
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="c-product__offer">
                        <div class="c-product__offer-title">
                            {{ __('page/listing.loan_title') }}
                        </div>
                        <div class="c-product__offer-desc">
                            {{ __('page/listing.loan_desc') }}
                        </div>
                        <div class="c-product__offer-body">
                            <div class="c-product__offer-input-wrapper">
                                <label for="inputPassword5" class="c-product__offer-label form-label">
                                    {{ __('page/listing.loan_amount') }}
                                </label>
                                <input type="number" step="0.0001" id="creditChange" class="c-product__offer-input form-control" @keyup="setLoan($event)" value="{{ sprintf('%.0f', $listing->price) }}" placeholder="{{ sprintf('%.0f', $listing->price) }} {{ $currency_label }}">
                            </div>
                            <button type="button" @click="goFinancePage()" class="c-button c-button--black c-button--block c-button--sm c-button--uppercase c-button--light mt-3">{{ __('module/button.calculate') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- product extras -->
        <div class="c-product__extras">
            <div class="c-title c-title--mb-normal">
                <h4 class="c-title__heading c-title__heading--regular">
                    {{ __('page/listing.extras') }}
                </h4>
            </div>

            @foreach($attribute_groups as $ag)
            <div class="c-product__extras-sub">
                <div class="c-product__extras-sub-title">
                    {{ Session::get('current_language') == 'ar' ? $ag->attribute_group->name_ar : $ag->attribute_group->name_en }}
                </div>
                <ul class="c-product__extras-list">
                    @foreach($ag->attributes as $aga)
                    <li class="c-product__extras-list-item">{{ $aga['name'] }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
        <!-- product location -->
        <div class="c-product__location">
            <div class="c-product__location-title">
                {{ __('page/listing.location') }}
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-8">
                    <div class="c-map">
                        <div id="gmap" class="gmap"></div>
                        <input type="hidden" id="latitude" value="{{ $listing->latitude }}">
                        <input type="hidden" id="longitude" value="{{ $listing->longitude }}">
                    </div>
                </div>
                <div class="col-md-8 offset-md-2 offset-lg-0 col-lg-4">

                    <div class="c-product__location-card">
                        @if($listing->user->type->id == '2')
                        <a href="{{ route('site.seller_detail',['slug' => $listing->user->slug.'-'.$listing->user->id, 'location' => Session::get('current_location')]) }}" class="c-product__provider">
                            <div class="c-product__provider-left">
                                @if (!is_null($listing->user->logo))
                                <img src="{{ secure_asset('storage/user/'.$listing->user->logo) }}" class="c-product__provider-img">
                                @else
                                <img src="{{ secure_asset('site/assets/images/cover-img.jpg') }}" class="c-product__provider-img">
                                @endif
                                <div class="c-product__provider-details">
                                    <span class="c-product__provider-company">{{ $listing->user->name }}</span>
                                    @if(!is_null($listing->user->country))
                                    <span class="c-product__provider-location">{{ $listing->user->country->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endif
                        @if($listing->user->type->id == '1')
                        <div class="c-product__provider">
                            <div class="c-product__provider-left">
                                <div class="c-product__provider-details">
                                    <span class="c-product__provider-company">{{ $listing->user->name }}</span>
                                    @if(!is_null($listing->user->country))
                                    <span class="c-product__provider-location">{{ $listing->user->country->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="c-product__location-address">
                            {{ $listing->country->name }} / {{ $listing->state->name }} / {{ !is_null($listing->city) ? $listing->city->name : $listing->state->name }}
                        </div>
                        @if(Auth::guard('member')->check())
                            @if(Auth::guard('member')->user()->user_guid != $listing->user_guid)
                            <button class="c-button c-button--gray c-button--block c-button--sm  c-button--uppercase" @click="openMessageModal()">
                                {{ __('module/button.send_message') }}
                            </button>
                            @endif
                        @else
                        <a href="{{ route('member.login') }}" class="c-button c-button--gray c-button--block c-button--sm  c-button--uppercase">
                            {{ __('module/button.send_message') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- offer -->
        <div class="c-modal modal fade" id="offerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="c-modal__dialog modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="c-modal__content modal-content">
                    <div class="c-modal__header modal-header">
                        <h5 class="c-modal__title modal-title" id="exampleModalLabel">{{ __('module/label.create_new_conversation') }}</h5>
                        <button type="button" class="c-button__close btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="c-modal__body modal-body p-0">
                        <div class="c-msg__right">
                            <div class="c-msg__right-head">
                                <div class="c-msg__right-head-info">
                                    <div class="c-msg__right-back">
                                        <svg class="c-icon__svg c-icon--sm">
                                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#chevron-left') }}"></use>
                                        </svg>
                                    </div>
                                    <p class="c-msg__right-name">{{ $listing->user->name }}</p>
                                    <p class="c-msg__right-tel">{{ $listing->user->phone }}</p>
                                </div>
                            </div>
                            <div class="alertbox">@{{ alertMsg }}</div>
                            <div class="c-msg__right-body" id="c-msg__right-body" style="min-height: 120px"></div>
                            @if(Auth::guard("member")->check())
                            <div class="c-msg__right-input">
                                <input type="text" placeholder="{{ __('user/messages.type_something') }}" @keyup="setMsg($event)">
                                <input type="hidden" id="receiver" value="{{ $listing->user->user_guid }}">
                                <input type="hidden" id="listing" value="{{ $listing->listing_no }}">
                                <input type="hidden" id="listingFav" value="{{ $is_fav }}">
                                <button type="button" @click="sendMessage()" class="c-button c-button--black c-button--sm c-button--uppercase c-button--xl-w m-1">{{ __('module/button.send') }}</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsFvopLdjzjionS5o3jpTMR4ffodY0a1k&language=tr"></script>
<script src="{{ secure_asset('site/vue/listing_detail.js') }}"></script>
@endsection
