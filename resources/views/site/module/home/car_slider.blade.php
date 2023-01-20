@foreach($all_categories as $k => $ac)
@php
    $catname = Session::get('current_language') == 'ar' ? $ac->name_ar : $ac->name_en;
@endphp
@if(count($ac['sale_listings']) > 0)
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-10">
                <div class="c-title">
                    <h4 class="c-title__heading">{{ __('page/home.featured_listing_for_sale_title', ['cat' => $catname]) }}</h4>
                </div>
            </div>
            <div class="col-2">
                <div class="c-slider__arrows">
                    <div class="c-slider__arrows-prev c-slider--showcase-2__prevBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ asset('site/assets/images/sprite.svg#arrow-left') }}"></use>
                        </svg>
                    </div>
                    <div class="c-slider__arrows-next c-slider--showcase-2__nextBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ asset('site/assets/images/sprite.svg#arrow-right') }}"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="l-container--left">
        <div class="owl-carousel owl-theme c-slider c-slider--overflowed c-slider--showcase-2">
            @foreach($ac['sale_listings'] as $list)
            <a class="c-showcase-card" href="{{ route('site.listing_detail', ['category' => $list->category->slug, 'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                <div class="c-showcase-card__body">
                    @if(!is_null($list->main_image) && file_exists('storage/listings/'.$list->listing_no.'/'.$list->main_image->name))
                    <img class="c-showcase-card__img" src="{{ asset('storage/listings/'.$list->listing_no.'/'.$list->main_image->name) }}" alt="">
                    @else
                    <img class="c-showcase-card__img" src="{{ asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                    @endif
                </div>
                <div class="c-showcase-card__footer">
                    <div class="c-showcase-card__footer-left">
                        <span class="c-showcase-card__title">{{ __('module/label.for_sale') }}</span>
                        <span class="c-showcase-card__location">{{ $list->country->name.' / '.$list->state->name }}</span>
                    </div>
                    <div class="c-showcase-card__footer-right">
                        <span class="c-showcase-card__price">
                            {{ $list->currency->symbol.' '.sprintf('%.0f',($list->price)) }}
                        </span>
                    </div>
                </div>
                <div class="c-showcase-card__bottom">
                    <div class="c-showcase-card__bottom-left">
                        <span>
                            {{ $list->brand->name_en.' '.$list->model->name_en.' / '.$list->year }}
                        </span>
                        <span>
                            {{ Session::get('current_language') == 'ar' ? $list->color->name_ar : $list->color->name_en }} / {{ $list->mileage.'KM' }}
                        </span>
                    </div>
                    @if($list->user->type->id == '2')
                    <div class="c-showcase-card__bottom-right">
                        @if(!is_null($list->user->logo) && file_exists('storage/user/'.$list->user->logo))
                        <img src="{{ asset('storage/user/'.$list->user->logo) }}" style="border-radius: 4px">
                        @else
                        <img src="{{ asset('site/assets/images/no-listing.png') }}" style="border-radius: 4px">
                        @endif
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@if(count($ac['rent_listings']) > 0)
<section class="c-section">
    <div class="container">
        <div class="row">
            <div class="col-10">
                <div class="c-title">
                    <h4 class="c-title__heading">{{ __('page/home.featured_listing_for_rent_title', ['cat' => $catname]) }}</h4>
                </div>
            </div>
            <div class="col-2">
                <div class="c-slider__arrows">
                    <div class="c-slider__arrows-prev c-slider--showcase-1__prevBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ asset('site/assets/images/sprite.svg#arrow-left') }}"></use>
                        </svg>
                    </div>
                    <div class="c-slider__arrows-next c-slider--showcase-1__nextBtn">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ asset('site/assets/images/sprite.svg#arrow-right') }}"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="l-container--left">
        <div class="owl-carousel owl-theme c-slider c-slider--overflowed c-slider--showcase-1">
            @foreach($ac['rent_listings'] as $list)
            <a class="c-showcase-card" href="{{ route('site.listing_detail', ['category' => $list->category->slug, 'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                <div class="c-showcase-card__body">
                    @if(!is_null($list->main_image) && file_exists('storage/listings/'.$list->listing_no.'/'.$list->main_image->name))
                    <img class="c-showcase-card__img" src="{{ asset('storage/listings/'.$list->listing_no.'/'.$list->main_image->name) }}" alt="">
                    @else
                    <img class="c-showcase-card__img" src="{{ asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                    @endif
                </div>
                <div class="c-showcase-card__footer">
                    <div class="c-showcase-card__footer-left">
                        <span class="c-showcase-card__title">{{ __('module/label.for_rent') }}</span>
                        <span class="c-showcase-card__location">{{ $list->country->name.' / '.$list->state->name }}</span>
                    </div>
                    <div class="c-showcase-card__footer-right">
                        <span class="c-showcase-card__price">
                            {{ $list->currency->symbol.' '.sprintf('%.0f',($list->price)) }}
                        </span>
                    </div>
                </div>
                <div class="c-showcase-card__bottom">
                    <div class="c-showcase-card__bottom-left">
                        <span>
                            {{ $list->brand->name_en.' '.$list->model->name_en.' / '.$list->year }}
                        </span>
                        <span>
                            {{ Session::get('current_language') == 'ar' ? $list->color->name_ar : $list->color->name_en }} / {{ $list->mileage.'KM' }}
                        </span>
                    </div>
                    @if($list->user->type->id == '2')
                    <div class="c-showcase-card__bottom-right">
                        @if(!is_null($list->user->logo) && file_exists('storage/user/'.$list->user->logo))
                        <img src="{{ asset('storage/user/'.$list->user->logo) }}" style="border-radius: 4px">
                        @else
                        <img src="{{ asset('site/assets/images/no-listing.png') }}" style="border-radius: 4px">
                        @endif
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@if($k == count($all_categories) - 1)
@include('site.module.home.expertise_advert')
@endif
@endforeach
