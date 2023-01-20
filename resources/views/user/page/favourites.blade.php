@extends('site.layout.master')

@section('main_content')
<section class="c-section c-section--sm-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 offset-xl-2 col-lg-10 offset-lg-1">
                <div class="c-title c-title--mb-large">
                    <h3 class="c-title__heading c-title__heading--regular">{{ __('user/favourites.title') }}</h3>
                    <p>{{ __('user/favourites.desc') }}</p>
                </div>
            </div>
        </div>

        <div class="row" id="favourites">
            <div class="d-none" id="delete_msg">{{ __('alert.fav_delete_msg') }}</div>
            @if(Session::has('success'))
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            </div>
            @endif
            @if(Session::has('error'))
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            </div>
            @endif
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                @if(count($favorites) > 0)
                    @foreach($favorites as $fav)
                    <div class="c-h-card">
                        <a href="{{ route('site.listing_detail', ['category' => $fav->listing->category->slug, 'listing_no' => $fav->listing->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank" class="c-h-card__img">
                            @if(!is_null($fav->listing->main_image) && file_exists('storage/listings/'.$fav->listing->listing_no.'/'.$fav->listing->main_image->name))
                            <img src="{{ secure_asset('storage/listings/'.$fav->listing->listing_no.'/'.$fav->listing->main_image->name) }}" alt="">
                            @else
                            <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" style="background: #f1f1f1">
                            @endif
                        </a>
                        <a href="{{ route('site.listing_detail', ['category' => $fav->listing->category->slug, 'listing_no' => $fav->listing->listing_no, 'location' => Session::get('current_location')]) }}" target="_blank" class="c-h-card__content">
                            <div class="c-h-card__content-head">
                                <div class="c-h-card__title c-h-card__title--uppercase">
                                    {{ $fav->listing->name_en }}
                                </div>
                                <div class="c-h-card__price c-h-card__price--14">
                                    {{ $fav->listing->currency->label.' '.$fav->listing->price }}
                                </div>
    
                                <div class="c-h-card__loc">
                                    {{ $fav->listing->country->name.' / '.$fav->listing->state->name }}
                                </div>
                            </div>
                        </a>
                        <div class="c-h-card__action">
                            <div class="c-h-card__dropdown dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                                <svg class="c-icon__svg c-icon--sm">
                                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#more') }}"></use>
                                </svg>
                                <div class="dropdown-menu dropdown-menu--more dropdown-menu-end"
                                    aria-labelledby="search">
                                    <div class="dropdown-menu--more__body">
                                        <span @click="deleteFav('{{ $fav->listing->listing_no }}','{{ $fav->listing->listing_guid }}')" class="dropdown-menu--more-item">
                                            {{ __('module/button.remove') }}
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
							<use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#star') }}"></use>
						</svg>
					</div>
					<div class="c-empty__body">
						{!! __('alert.dont_have_favourites') !!}
					</div>
				</div>
                @endif
            </div>

            <div class="c-modal modal fade" id="deleteFavModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="c-modal__dialog modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <form method="post" action="{{ route('member.delete_fav') }}" class="c-modal__content modal-content">
                        @csrf
                        <input type="hidden" name="listing_guid" :value="listingNo">
                        <div class="c-modal__header modal-header">
                            <h5 class="c-modal__title modal-title" id="exampleModalLabel">{{ __('module/label.delete_fav_title') }}</h5>
                            <button type="button" class="c-button__close btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="c-modal__body modal-body" v-html="deleteFavText">
                        </div>
                        <div class="c-modal__footer modal-footer">
                            <button type="submit" class="c-button c-button--black c-button--uppercase c-button--11 c-button--sm c-button--lg-w">
                                {{ __('module/button.delete') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script src="{{ secure_asset('site/vue/members/favourites.js') }}"></script>
@endsection