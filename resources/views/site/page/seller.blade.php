@extends('site.layout.master')

@section('main_content')
<section class="c-section c-section--sm-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="c-cover">
                    @if (!is_null($user->background))
                    <img class="c-cover__img" src="{{ secure_asset('storage/user/'.$user->background) }}" alt="">
                    @else
                    <img class="c-cover__img" src="{{ secure_asset('site/assets/images/cover-img.jpg') }}" alt="">
                    @endif
                    <div class="c-cover__card">
                        <div class="c-cover__card-head">
                            <div class="c-cover__card-identy">
                                <div class="c-cover__card-identy-logo">
                                    @if (!is_null($user->logo))
                                    <img src="{{ secure_asset('storage/user/'.$user->logo) }}" alt="">
                                    @else
                                    <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" alt="">
                                    @endif
                                </div>
                                <div class="c-cover__card-identy-detail">
                                    <div class="c-cover__card-identy-title">
                                        {{ $user->name }}
                                    </div>
                                    <div class="c-cover__card-identy-loc">
                                        {{ !is_null($user->country) ? $user->country->name : '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="c-cover__card-action">
                                <div class="c-cover__card-action-item">
                                    <a href="tel:{{ $phone }}"
                                        class="c-button c-button--gray c-button--only-icon c-button--lg">
                                        <svg class="c-icon__svg c-icon--md">
                                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#phone') }}"></use>
                                        </svg>
                                    </a>
                                </div>

                                <div class="c-cover__card-action-item">
                                    <div class="c-badge c-badge--gray c-badge--icon-black">
                                        <svg class="c-icon__svg c-icon--md">
                                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#badge-check') }}"></use>
                                        </svg>
                                        <p>{{ __('page/listing.member_since',['year' => date('Y', strtotime($user->created_at))]) }}</p>
                                    </div>
                                    </a>
                                </div>

                            </div>
                        </div>
                        @if(!is_null($user->description))
                        <div class="c-cover__card-body">
                            <div class="c-cover__card-desc">
                                <div class="c-cover__card-desc-title">
                                    {{ __('module/label.description') }}:
                                </div>
                                <div class="c-cover__card-desc-content">
                                    {!! $user->description !!}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="c-section c-section--no-padding-top">
    <div class="container">
        <div class="row">
            <!-- tabs -->
            <div class="col-xl-8 offset-xl-1 col-lg-8 offset-lg-1 col-md-9">
                <ul class="nav nav-pills c-light-tab__pills" id="pills-tab">
                    <li class="nav-item c-light-tab__item" role="presentation">
                        <button class="nav-link c-light-tab__link active" id="pills-corp-all-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-corp-all" type="button" aria-controls="pills-corp-all"
                            aria-selected="false">All</button>
                    </li>
                    @foreach($categories as $c)
                    @if(count($listings[$c->slug]) > 0)
                    <li class="nav-item c-light-tab__item">
                        <button class="nav-link c-light-tab__link" id="pills-corp-car-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-corp-{{ $c->slug }}" type="button"
                            aria-controls="pills-corp-car" aria-selected="true">{{ Session::get('current_language') == 'ar' ? $c->name_ar : $c->name_en }}</button>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
            <form class="col-xl-2 col-lg-2 col-md-3 d-flex justify-content-end align-items-start" id="filter">
                <select class="js-sort-select c-select2--sort" name="filter" style="width: 150px;">
                    <option value=""></option>
                    <option value="mileage-desc" {{ request()->get('filter') == 'mileage-desc' ? 'selected' : null }}>{{ __('page/seller.filter.by_km_desc') }}</option>
                    <option value="mileage-asc" {{ request()->get('filter') == 'mileage-asc' ? 'selected' : null }}>{{ __('page/seller.filter.by_km_asc') }}</option>
                    <option value="year-desc" {{ request()->get('filter') == 'year-desc' ? 'selected' : null }}>{{ __('page/seller.filter.by_year_desc') }}</option>
                    <option value="year-asc" {{ request()->get('filter') == 'year-desc' ? 'selected' : null }}>{{ __('page/seller.filter.by_year_asc') }}</option>
                </select>
            </form>
            <div class="col-xl-10 offset-xl-1 col-lg-10 offset-lg-1">
                <div class="tab-content c-light-tab__content" id="pills-tabContent">
                    <div class="tab-pane c-light-tab__pane fade show active" id="pills-corp-all"
                        aria-labelledby="pills-corp-all-tab">
                        @foreach($listings as $k => $listing)
                            @foreach($listing as $list)
                            <div class="c-h-card">
                                <a class="c-h-card__img" href="{{ route('site.listing_detail', ['category' => $k, 'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                                    @if(!is_null($list->main_image) && file_exists('storage/listings/'.$list->listing_no.'/'.$list->main_image->name))
                                    <img src="{{ secure_asset('storage/listings/'.$list->listing_no.'/'.$list->main_image->name) }}" alt="">
                                    @else
                                    <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                                    @endif
                                </a>
                                <a class="c-h-card__content" href="{{ route('site.listing_detail', ['category' => $k, 'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                                    <div class="c-h-card__content-head">
                                        <div class="c-h-card__title">
                                            {{ Session::get('current_language') == 'ar' ? $list->name_ar : $list->name_en }}
                                        </div>
                                        <div class="c-h-card__model">
                                            <span>{{ '#'.$list->listing_no }}</span>
                                            @php
                                                if(!is_null($list->brand)) {
                                                    if(!is_null($list->model)) {
                                                        echo ' - '.$list->brand->name_en.' '.$list->model->name_en;
                                                    } else {
                                                        echo ' - '.$list->brand->name_en;
                                                    }
                                                }
                                            @endphp
                                            <span> - {{ $list->year }}</span>
                                            <span> - {{ $list->mileage.' KM' }}</span>
                                        </div>
                                        <div class="c-h-card__price c-h-card__price--mobile">{{ $list->currency->label }} {{ sprintf('%.0f', $list->price) }}</div>
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
                                    <div class="c-h-card__price c-h-card__price--desktop">{{ $list->currency->label }} {{ sprintf('%.0f', $list->price) }}</div>
                                </div>
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                    @foreach($categories as $c)
                    @if(count($listings[$c->slug]) > 0)
                    <div class="tab-pane c-light-tab__pane fade" id="pills-corp-{{ $c->slug }}"
                        aria-labelledby="pills-corp-car-tab">
                        @foreach($listings[$c->slug] as $list)
                        <div class="c-h-card">
                            <a class="c-h-card__img" href="{{ route('site.listing_detail', ['category' => $c->slug, 'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                                @if(!is_null($list->main_image) && file_exists('storage/listings/'.$list->listing_no.'/'.$list->main_image->name))
                                <img src="{{ secure_asset('storage/listings/'.$list->listing_no.'/'.$list->main_image->name) }}" alt="">
                                @else
                                <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                                @endif
                            </a>
                            <a class="c-h-card__content" href="{{ route('site.listing_detail', ['category' => $c->slug, 'listing_no' => $list->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank">
                                <div class="c-h-card__content-head">
                                    <div class="c-h-card__title">
                                        {{ Session::get('current_language') == 'ar' ? $list->name_ar : $list->name_en }}
                                    </div>
                                    <div class="c-h-card__model">
                                        <span>{{ '#'.$list->listing_no }}</span>
                                        @php
                                            if(!is_null($list->brand)) {
                                                if(!is_null($list->model)) {
                                                    echo ' - '.$list->brand->name_en.' '.$list->model->name_en;
                                                } else {
                                                    echo ' - '.$list->brand->name_en;
                                                }
                                            }
                                        @endphp
                                        <span> - {{ $list->year }}</span>
                                        <span> - {{ $list->mileage.' KM' }}</span>
                                    </div>
                                    <div class="c-h-card__price c-h-card__price--mobile">{{ $list->currency->label }} {{ sprintf('%.0f', $list->price) }}</div>
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
                                <div class="c-h-card__price c-h-card__price--desktop">{{ $list->currency->label }} {{ sprintf('%.0f', $list->price) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script>
        $('.js-sort-select').select2({
            minimumResultsForSearch: Infinity,
            dropdownCssClass: 'c-select2--air__dropdown',
            placeholder: 'A TO Z',
        }).on('select2:selecting', function(e) {
            setTimeout(() => {
                $('#filter').submit();
                $('.js-sort-select').val(e.params.args.data.id);
            }, 150);
        });
    </script>
@endsection
