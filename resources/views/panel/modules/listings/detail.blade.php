<!--start::Statistics-->
<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $listing->images_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Images</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $listing->messages_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a
                        href="{{ route('admin.messages.home') }}?search={{ $listing->listing_no }}"
                        target="_blank">Message</a></span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $listing->favorite_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Favorite</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $attribute_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Attribute</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
</div>
<!--end::Statistics-->

<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-success nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active general_settings" data-toggle="tab" href="#general_settings">
                                <span class="nav-text">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link images" data-toggle="tab" href="#images">
                                <span class="nav-text">Image Settings</span>
                            </a>
                        </li>
                        @if (count($attribute_groups) > 0)
                        <li class="nav-item">
                            <a class="nav-link attribute_group" data-toggle="tab" href="#attribute_group">
                                <span class="nav-text">Attribute Group Settings</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-toolbar">
                    <span class="label label-outline-danger label-inline mr-2"> EXCHANGE RATE LAST UPDATED {{ $diffHours
                        }}
                        HOURS AGO.</span>
                    <span class="label label-outline-info label-inline mr-2">Expire {{
                        \Carbon\Carbon::parse($listing->expire_at)->diffInDays() }} days now</span>
                    <button guid="{{ $listing->listing_guid }}" cname="{{ $listing->name_en }}" type="button"
                        class="btn btn-sm btn-danger expire_btn ml-2" data-toggle="modal"
                        data-target="#expire_listing_modal">
                        Extend Expiry Date
                    </button>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="example-preview">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active general_settings" id="general_settings"
                                    role="tabpanel" aria-labelledby="general_settings">

                                    @include('panel.modules.listings.modules.listing_settings')

                                </div>

                                <div class="tab-pane fade images" id="images" role="tabpanel" aria-labelledby="images">


                                    @include('panel.modules.listings.modules.listing_images')

                                </div>
                                @if (count($attribute_groups) > 0)
                                <div class="tab-pane fade attribute_group" id="attribute_group" role="tabpanel"
                                    aria-labelledby="attribute_group">


                                    @include('panel.modules.listings.modules.listing_attribute_groups')

                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--listing expire modal-->
<div class="modal fade" id="expire_listing_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="listingModalLabel">Extend Expiry Date</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_listing">

            </div>

        </div>

        <form class="w-100" method="post" action="{{ route('admin.listings.expiry_day') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="listing_guid" id="listing_guid" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Expiry</button>
            </div>

        </form>


    </div>
</div>
@section('js')
<script type="module" src="{{ secure_asset('panel/assets/js/brands.js')}}"></script>
<script type="module" src="{{ secure_asset('panel/assets/js/categories.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsFvopLdjzjionS5o3jpTMR4ffodY0a1k&language=tr"></script>
<script type="module" src="{{ secure_asset('panel/assets/js/googleMap.js')}}"></script>
<script>
    const Routes = {
        select_brand : "{{ route('admin.listings.select_brand') }}",
        select_model : "{{ route('admin.listings.select_model') }}",
        select_category : "{{ route('admin.listings.select_category') }}",
    }

    $('.summernote2').summernote({
                toolbar: [
                    ['cleaner', ['cleaner']],
                    ['style', ['style', 'bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['table', ['table']],
                ],
                fontNames: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
                fontNamesIgnoreCheck: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
                cleaner: {
                    action: 'paste',
                },
                height: 300,
                resize: false,
                lang: 'en-US',
            });

    const expire_btn = document.getElementsByClassName('expire_btn');
    Array.from(expire_btn).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('listing_guid');
            const nameModalDelete = document.getElementById('name_modal_listing');
            nameModalDelete.innerHTML = `You are about to extend an listing. Are you sure you want to extend ${cname}?`;
            hiddenBtn.value = guid;
        });
    });

    const imagesGuidBtns = document.getElementsByClassName('delete_btns');
    Array.from(imagesGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an listing image. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });

    @if(Session::has('successDeleteImage'))
        toastr.success('You have successfully delete image.');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const images = document.getElementsByClassName('images')[1];
        images.classList.add('show');
        images.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const imagesTab = document.getElementsByClassName('images')[0];
        imagesTab.classList.add('active');
    @endif

    @if(Session::has('successUpdateListing'))
    toastr.success('Listing successfully update.');
    @endif

    @if(Session::has('errorUpdateListing'))
    toastr.error('Fill in the required fields');
    @endif

    @if(Session::has('successUpdateListing'))
    toastr.warning('Pay attention to the exchange rate, the price may not be correct!');
    @endif

    @if(Session::has('successUpdateExpiryDay'))
    toastr.success('You have successfully extended the expiration date.');
    @endif

    @if(Session::has('successUpdateAttributeGroup'))
        toastr.success('You have successfully update Attribute Group.');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const attribute_group = document.getElementsByClassName('attribute_group')[1];
        attribute_group.classList.add('show');
        attribute_group.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const attribute_groupTab = document.getElementsByClassName('attribute_group')[0];
        attribute_groupTab.classList.add('active');
    @endif

</script>
@endsection
