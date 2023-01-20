@extends('site.layout.master')

@section('main_content')
<div id="member_listings">
    <div class="d-none" id="delete_msg">{{ __('alert.listing_delete_msg') }}</div>
    <div class="d-none" id="enable_msg">{{ __('alert.listing_enable_msg') }}</div>
    <div class="d-none" id="disable_msg">{{ __('alert.listing_disable_msg') }}</div>
    <section class="c-section c-section--sm-bottom">
        <div class="container">
            <div class="row">
                <!-- page title -->
                <div class="col-xl-6 offset-xl-2 col-lg-7 offset-lg-1 col-6">
                    <div class="c-title">
                        <h3 class="c-title__heading c-title__heading--regular">{{ __('user/listings.list_title') }}</h3>
                    </div>
                </div>
                @if($current_limit < $listing_limit)
                <div class="col-xl-2 col-lg-3 col-6 d-flex justify-content-end align-items-start">
                    <a href="{{ route('member.new_listing') }}" class="c-badge c-badge--gray">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus') }}"></use>
                        </svg>
                        <p>{{ __('user/listings.add_listing') }}</p>
                    </a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                    <div class="c-iconbox__wrapper">
                        <span class="c-iconbox">
                            <div class="c-iconbox__icon">
                                <svg class="c-icon__svg c-icon--lg">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#listing') }}"></use>
                                </svg>
                            </div>
                            <div class="c-iconbox__content">
                                <div class="c-iconbox__title">{{ __('user/listings.total_listing') }}</div>
                                <div class="c-iconbox__data">{{ $total_listings }}</div>
                            </div>
                        </span>
                        <div class="c-iconbox">
                            <div class="c-iconbox__icon">
                                <svg class="c-icon__svg c-icon--lg">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#credit') }}"></use>
                                </svg>
                            </div>
                            <div class="c-iconbox__content">
                                <div class="c-iconbox__title">{{ __('user/listings.active_passive') }}</div>
                                <div class="c-iconbox__data">{{ $active_passive }}</div>
                            </div>
                        </div>
                        <div class="c-iconbox">
                            <div class="c-iconbox__icon">
                                <svg class="c-icon__svg c-icon--lg">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#available') }}"></use>
                                </svg>
                            </div>
                            <div class="c-iconbox__content">
                                <div class="c-iconbox__title">{{ __('user/listings.total_views') }}</div>
                                <div class="c-iconbox__data">{{ $total_views }}</div>
                            </div>
                        </div>
                        <div class="c-iconbox">
                            <div class="c-iconbox__icon">
                                <svg class="c-icon__svg c-icon--lg">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star') }}"></use>
                                </svg>
                            </div>
                            <div class="c-iconbox__content">
                                <div class="c-iconbox__title">{{ __('user/listings.faved_listings') }}</div>
                                <div class="c-iconbox__data">{{ $favs }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- tabs -->
                @if(Session::has('success'))
                    <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                        <div class="alert alert-warning">
                            {{ Session::get('error') }}
                        </div>
                    </div>
                @endif
                <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                    <ul class="nav nav-pills c-light-tab__pills" id="pills-tab">
                        <li class="nav-item c-light-tab__item">
                            <button class="nav-link c-light-tab__link active" id="pills-active-listing-tab" data-bs-toggle="pill" data-bs-target="#pills-active-listing" type="button" aria-controls="pills-active-listing" aria-selected="true">{{ __('user/listings.active_listings') }}</button>
                        </li>
                        <li class="nav-item c-light-tab__item" role="presentation">
                            <button class="nav-link c-light-tab__link" id="pills-passive-listing-tab" data-bs-toggle="pill" data-bs-target="#pills-passive-listing" type="button" aria-controls="pills-passive-listing" aria-selected="false">{{ __('user/listings.passive_listings') }}</button>
                        </li>
                        <li class="nav-item c-light-tab__item" role="presentation">
                            <button class="nav-link c-light-tab__link" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" aria-controls="pills-all" aria-selected="false">{{ __('user/listings.all_listings') }}</button>
                        </li>

                    </ul>
                    <div class="tab-content c-light-tab__content" id="pills-tabContent">
                        <div class="tab-pane c-light-tab__pane fade active show" id="pills-active-listing" aria-labelledby="pills-active-listing-tab">
                            @if(count($active_listings) > 0)
                                @foreach($active_listings as $list)
                                <div class="c-h-card">
                                    <a class="c-h-card__img" href="{{ route('member.listing_detail', ['listing_no' => $list->listing_no]) }}">
                                        @if(!is_null($list->main_image) && file_exists('storage/listings/'.$list->listing_no.'/'.$list->main_image->name))
                                        <img src="{{ secure_asset('storage/listings/'.$list->listing_no.'/'.$list->main_image->name) }}" alt="">
                                        @else
                                        <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                                        @endif
                                    </a>
                                    <a class="c-h-card__content" href="{{ route('member.listing_detail', ['listing_no' => $list->listing_no]) }}"> 
                                        <div class="c-h-card__content-head">
                                            <div class="c-h-card__title">
                                                {{ Session::get('current_language') == 'ar' ? $list->name_ar : $list->name_en }}
                                            </div>
                                            <div class="c-h-card__model">
                                                <span>{{ '#'.$list->listing_no }}</span>
                                                @php
                                                    if(!is_null($list->brand)) {
                                                        if(!is_null($list->model)) {
                                                            if(!is_null($list->trim)) {
                                                                echo ' - '.$list->brand->name_en.' '.$list->model->name_en.' '.$list->trim->name_en;
                                                            } else {
                                                                echo ' - '.$list->brand->name_en.' '.$list->model->name_en;
                                                            }
                                                        } else {
                                                            echo ' - '.$list->brand->name_en;
                                                        }
                                                    }
                                                @endphp
                                            </div>
                                            <div class="c-h-card__price c-h-card__price--mobile">{{ $list->currency->label }} {{ $list->price }}</div>
                                        </div>
                                        <div class="c-h-card__content-footer">
                                            <div class="c-h-card__info">
                                                <svg class="c-icon__svg c-icon--sm">
                                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#eye') }}"></use>
                                                </svg>
                                                <span>{{ $list->viewed }}</span>
                                            </div>
                                            <div class="c-h-card__info">
                                                <svg class="c-icon__svg c-icon--sm">
                                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star--filled') }}"></use>
                                                </svg>
                                                <span>{{ $list->favorite_count }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="c-h-card__action">
                                        <div class="c-h-card__price c-h-card__price--desktop">{{ $list->currency->label }} {{ $list->price }}</div>
                                        <div class="c-h-card__dropdown dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                                            <svg class="c-icon__svg c-icon--sm">
                                                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#more') }}"></use>
                                            </svg>
                                            <div class="dropdown-menu dropdown-menu--more dropdown-menu-end" aria-labelledby="search">
                                                <form class="dropdown-menu--more__body">
                                                    <span href="#" class="dropdown-menu--more-item" @click="deleteListing('{{ $list->listing_no }}')">
                                                        {{ __('module/button.delete_listing') }}
                                                    </span>
                                                    <span href="#" class="dropdown-menu--more-item" @click="disableListing('{{ $list->listing_no }}')">
                                                        {{ __('module/button.disable_listing') }}
                                                    </span>
                                                    <a class="dropdown-menu--more-item" href="{{ route('site.listing_detail', ['category' => $list->category->slug, 'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                                                        {{ __('module/button.view_as_client') }}
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="c-empty">
								<div class="c-empty__head">
									<svg class="c-icon__svg c-icon--xl">
										<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus-rounded') }}"></use>
									</svg>
								</div>
								<div class="c-empty__body">
									{!! __('alert.no_active_listings') !!}
								</div>
                                @if($current_limit < $listing_limit)
								<a href="{{ route('member.new_listing') }}" class="c-button c-button--gray c-button--sm c-button--sm-w">
									<svg class="c-icon__svg c-icon--sm">
										<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus') }}"></use>
									</svg>
									{{ __('user/listings.add_listing') }}
								</a>
                                @endif
							</div>
                            @endif
                        </div>
                        <div class="tab-pane c-light-tab__pane fade" id="pills-passive-listing" aria-labelledby="pills-passive-listing-tab">
                            @if(count($passive_listings) > 0)
                                @foreach($passive_listings as $list)
                                <div class="c-h-card">
                                    <a class="c-h-card__img" href="{{ route('member.listing_detail', ['listing_no' => $list->listing_no]) }}">
                                        @if(!is_null($list->main_image) && file_exists('storage/listings/'.$list->listing_no.'/'.$list->main_image->name))
                                        <img src="{{ secure_asset('storage/listings/'.$list->listing_no.'/'.$list->main_image->name) }}" alt="">
                                        @else
                                        <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                                        @endif
                                    </a>
                                    <a class="c-h-card__content" href="{{ route('member.listing_detail', ['listing_no' => $list->listing_no]) }}"> 
                                        <div class="c-h-card__content-head">
                                            <div class="c-h-card__title">
                                                {{ Session::get('current_language') == 'ar' ? $list->name_ar : $list->name_en }}
                                            </div>
                                            <div class="c-h-card__model">
                                                <span>{{ '#'.$list->listing_no }}</span>
                                                @php
                                                    if(!is_null($list->brand)) {
                                                        if(!is_null($list->model)) {
                                                            if(!is_null($list->trim)) {
                                                                echo ' - '.$list->brand->name_en.' '.$list->model->name_en.' '.$list->trim->name_en;
                                                            } else {
                                                                echo ' - '.$list->brand->name_en.' '.$list->model->name_en;
                                                            }
                                                        } else {
                                                            echo ' - '.$list->brand->name_en;
                                                        }
                                                    }
                                                @endphp
                                            </div>
                                            <div class="c-h-card__price c-h-card__price--mobile">{{ $list->currency->label }} {{ $list->price }}</div>
                                        </div>
                                        <div class="c-h-card__content-footer">
                                            <div class="c-h-card__info">
                                                <svg class="c-icon__svg c-icon--sm">
                                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#eye') }}"></use>
                                                </svg>
                                                <span>{{ $list->viewed }}</span>
                                            </div>
                                            <div class="c-h-card__info">
                                                <svg class="c-icon__svg c-icon--sm">
                                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star--filled') }}"></use>
                                                </svg>
                                                <span>{{ $list->favorite_count }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="c-h-card__action">
                                        <div class="c-h-card__price c-h-card__price--desktop">{{ $list->currency->label }} {{ $list->price }}</div>
                                        <div class="c-h-card__dropdown dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                                            <svg class="c-icon__svg c-icon--sm">
                                                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#more') }}"></use>
                                            </svg>
                                            <div class="dropdown-menu dropdown-menu--more dropdown-menu-end" aria-labelledby="search">
                                                <div class="dropdown-menu--more__body">
                                                    <span href="#" class="dropdown-menu--more-item" @click="deleteListing('{{ $list->listing_no }}')">
                                                        {{ __('module/button.delete_listing') }}
                                                    </span>
                                                    <span href="#" class="dropdown-menu--more-item" @click="enableListing('{{ $list->listing_no }}')">
                                                        {{ __('module/button.enable_listing') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="c-empty">
								<div class="c-empty__head">
									<svg class="c-icon__svg c-icon--xl">
										<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus-rounded') }}"></use>
									</svg>
								</div>
								<div class="c-empty__body">
									{!! __('alert.no_passive_listings') !!}
								</div>
                                @if($current_limit < $listing_limit)
								<a href="{{ route('member.new_listing') }}" class="c-button c-button--gray c-button--sm c-button--sm-w">
									<svg class="c-icon__svg c-icon--sm">
										<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus') }}"></use>
									</svg>
									{{ __('user/listings.add_listing') }}
								</a>
                                @endif
							</div>
                            @endif
                        </div>
                        <div class="tab-pane c-light-tab__pane fade" id="pills-all" aria-labelledby="pills-all-tab">
                            @if(count($listings) > 0)
                                @foreach($listings as $list)
                                <div class="c-h-card">
                                    <a class="c-h-card__img" href="{{ route('member.listing_detail', ['listing_no' => $list->listing_no]) }}">
                                        @if(!is_null($list->main_image) && file_exists('storage/listings/'.$list->listing_no.'/'.$list->main_image->name))
                                        <img src="{{ secure_asset('storage/listings/'.$list->listing_no.'/'.$list->main_image->name) }}" alt="">
                                        @else
                                        <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                                        @endif
                                    </a>
                                    <a class="c-h-card__content" href="{{ route('member.listing_detail', ['listing_no' => $list->listing_no]) }}"> 
                                        <div class="c-h-card__content-head">
                                            <div class="c-h-card__title">
                                                {{ Session::get('current_language') == 'ar' ? $list->name_ar : $list->name_en }}
                                            </div>
                                            <div class="c-h-card__model">
                                                <span>{{ '#'.$list->listing_no }}</span>
                                                @php
                                                    if(!is_null($list->brand)) {
                                                        if(!is_null($list->model)) {
                                                            if(!is_null($list->trim)) {
                                                                echo ' - '.$list->brand->name_en.' '.$list->model->name_en.' '.$list->trim->name_en;
                                                            } else {
                                                                echo ' - '.$list->brand->name_en.' '.$list->model->name_en;
                                                            }
                                                        } else {
                                                            echo ' - '.$list->brand->name_en;
                                                        }
                                                    }
                                                @endphp
                                            </div>
                                            <div class="c-h-card__price c-h-card__price--mobile">{{ $list->currency->label }} {{ $list->price }}</div>
                                        </div>
                                        <div class="c-h-card__content-footer">
                                            <div class="c-h-card__info">
                                                <svg class="c-icon__svg c-icon--sm">
                                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#eye') }}"></use>
                                                </svg>
                                                <span>{{ $list->viewed }}</span>
                                            </div>
                                            <div class="c-h-card__info">
                                                <svg class="c-icon__svg c-icon--sm">
                                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star--filled') }}"></use>
                                                </svg>
                                                <span>{{ $list->favorite_count }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="c-h-card__action">
                                        <div class="c-h-card__price c-h-card__price--desktop">{{ $list->currency->label }} {{ $list->price }}</div>
                                        <div class="c-h-card__dropdown dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                                            <svg class="c-icon__svg c-icon--sm">
                                                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#more') }}"></use>
                                            </svg>
                                            <div class="dropdown-menu dropdown-menu--more dropdown-menu-end" aria-labelledby="search">
                                                <div class="dropdown-menu--more__body">
                                                    <span href="#" class="dropdown-menu--more-item" @click="deleteListing('{{ $list->listing_no }}')">
                                                        {{ __('module/button.delete_listing') }}
                                                    </span>
                                                    @if($list->status == '1')
                                                    <span href="#" class="dropdown-menu--more-item" @click="disableListing('{{ $list->listing_no }}')">
                                                        {{ __('module/button.disable_listing') }}
                                                    </span>
                                                    @endif
                                                    @if($list->status == '0')
                                                    <span href="#" class="dropdown-menu--more-item" @click="enableListing('{{ $list->listing_no }}')">
                                                        {{ __('module/button.enable_listing') }}
                                                    </span>
                                                    @endif
                                                    @if($list->status == '1')
                                                    <a class="dropdown-menu--more-item" href="{{ route('site.listing_detail', ['category' => $list->category->slug,'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                                                        {{ __('module/button.view_as_client') }}
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="c-empty">
								<div class="c-empty__head">
									<svg class="c-icon__svg c-icon--xl">
										<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus-rounded') }}"></use>
									</svg>
								</div>
								<div class="c-empty__body">
									{!! __('alert.no_listings') !!}
								</div>
                                @if($current_limit < $listing_limit)
								<a href="{{ route('member.new_listing') }}" class="c-button c-button--gray c-button--sm c-button--sm-w">
									<svg class="c-icon__svg c-icon--sm">
										<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus') }}"></use>
									</svg>
									{{ __('user/listings.add_listing') }}
								</a>
                                @endif
							</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="c-modal modal fade" id="deleteListingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="c-modal__dialog modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form method="post" action="{{ route('member.delete_listing') }}" class="c-modal__content modal-content">
                @csrf
                <input type="hidden" name="listing_no" :value="listingNo">
                <div class="c-modal__header modal-header">
                    <h5 class="c-modal__title modal-title" id="exampleModalLabel">{{ __('module/label.delete_listing_title') }}</h5>
                    <button type="button" class="c-button__close btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="c-modal__body modal-body" v-html="deleteText"></div>
                <div class="c-modal__footer modal-footer">
                    <button type="submit" class="c-button c-button--black c-button--uppercase c-button--11 c-button--sm c-button--lg-w">
                        {{ __('module/button.delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="c-modal modal fade" id="enableListingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="c-modal__dialog modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form method="post" action="{{ route('member.enable_listing') }}" class="c-modal__content modal-content">
                @csrf
                <input type="hidden" name="listing_no" :value="listingNo">
                <div class="c-modal__header modal-header">
                    <h5 class="c-modal__title modal-title" id="exampleModalLabel">{{ __('module/label.enable_listing_title') }}</h5>
                    <button type="button" class="c-button__close btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="c-modal__body modal-body" v-html="enableText"></div>
                <div class="c-modal__footer modal-footer">
                    <button type="submit" class="c-button c-button--black c-button--uppercase c-button--11 c-button--sm c-button--lg-w">
                        {{ __('module/button.enable') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="c-modal modal fade" id="disableListingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="c-modal__dialog modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form method="post" action="{{ route('member.disable_listing') }}" class="c-modal__content modal-content">
                @csrf
                <input type="hidden" name="listing_no" :value="listingNo">
                <div class="c-modal__header modal-header">
                    <h5 class="c-modal__title modal-title" id="exampleModalLabel">{{ __('module/label.disable_listing_title') }}</h5>
                    <button type="button" class="c-button__close btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="c-modal__body modal-body" v-html="disableText"></div>
                <div class="c-modal__footer modal-footer">
                    <button type="submit" class="c-button c-button--black c-button--uppercase c-button--11 c-button--sm c-button--lg-w">
                        {{ __('module/button.disable') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ secure_asset('site/vue/members/listings.js') }}"></script>
@endsection