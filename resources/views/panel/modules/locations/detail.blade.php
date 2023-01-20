<!--start::Statistics-->
<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $location->location_categories_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Category</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    {{-- <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $location->location_user_types_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm">User Types</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div> --}}
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $location->listings_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a
                        href="{{ route('admin.listings.home') }}?location_guid={{ $location->location_guid }}"
                        target="_blank">Listings</a></span>
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
                            <a class="nav-link categories" data-toggle="tab" href="#categories">
                                <span class="nav-text">Categories Settings</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link user_type" data-toggle="tab" href="#user_type">
                                <span class="nav-text">User Type Settings</span>
                            </a>
                        </li> --}}
                    </ul>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="example-preview">
                            <div class="tab-content" id="myTabContent">



                                <div class="tab-pane fade show active general_settings" id="general_settings"
                                    role="tabpanel" aria-labelledby="general_settings">

                                    @include('panel.modules.locations.modules.location_settings')

                                </div>

                                <div class="tab-pane fade categories" id="categories" role="tabpanel"
                                    aria-labelledby="categories">


                                    @include('panel.modules.locations.modules.location_categories')

                                </div>
                                {{-- <div class="tab-pane fade user_type" id="user_type" role="tabpanel"
                                    aria-labelledby="user_type">


                                    @include('panel.modules.locations.modules.location_user_types')

                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input id="location_guid_general" name="location_guid_general" type="hidden" value="{{ $location->location_guid }}">
@section('js')
<script>
    const location_guid_general = document.getElementById('location_guid_general');
    const Routes = {
        select_country : "{{ route('admin.locations.select_country') }}",
    }


</script>
<script type="module" src="{{ secure_asset('panel/assets/js/countries.js')}}"></script>
<script>
    @if(isset(request()->user_types) || $search_user_types == 1)
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const user_typeTab = document.getElementsByClassName('user_type')[0];
        user_typeTab.classList.add('active');
        const user_type = document.getElementsByClassName('user_type')[1];
        user_type.classList.add('show');
        user_type.classList.add('active');
    @endif

    @if(isset(request()->category) || $search_categories === 1)
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const categoriesTab = document.getElementsByClassName('categories')[0];
        categoriesTab.classList.add('active');
        const categories = document.getElementsByClassName('categories')[1];
        categories.classList.add('show');
        categories.classList.add('active');
    @endif

    $('#kt_select2_3').select2({});
    $('#kt_select2_4').select2({});

    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif

    @if(Session::has('successUpdateLocationCity'))
        toastr.success('Country and city successfully updated.');
    @endif

    @if(Session::has('successUpdateLocationCountry'))
        toastr.success('Country successfully updated.');
    @endif

    @if(Session::has('successUpdateLocationCurrency'))
        toastr.success('Currency successfully updated.');
    @endif

    @if(Session::has('successAddLocation'))
        toastr.success('Location successfully added.');
    @endif

    @if(Session::has('successUpdateLocation'))
        toastr.success('Location successfully updated.');
    @endif

    @if(Session::has('successAddedCategory'))
        toastr.success('Category successfully added.');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const categoriesTab = document.getElementsByClassName('categories')[0];
        categoriesTab.classList.add('active');
        const categories = document.getElementsByClassName('categories')[1];
        categories.classList.add('show');
        categories.classList.add('active');
    @endif

    @if(Session::has('successDeletedCategory'))
        toastr.success('Category successfully deleted.');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const categoriesTab = document.getElementsByClassName('categories')[0];
        categoriesTab.classList.add('active');
        const categories = document.getElementsByClassName('categories')[1];
        categories.classList.add('show');
        categories.classList.add('active');
    @endif

    @if(Session::has('successAddedUserType'))
        toastr.success('User Type successfully added.');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const user_typeTab = document.getElementsByClassName('user_type')[0];
        user_typeTab.classList.add('active');
        const user_type = document.getElementsByClassName('user_type')[1];
        user_type.classList.add('show');
        user_type.classList.add('active');
    @endif

    @if(Session::has('successDeletedUserType'))
        toastr.success('User Type successfully deleted.');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const user_typeTab = document.getElementsByClassName('user_type')[0];
        user_typeTab.classList.add('active');
        const user_type = document.getElementsByClassName('user_type')[1];
        user_type.classList.add('show');
        user_type.classList.add('active');
    @endif
</script>
@endsection
